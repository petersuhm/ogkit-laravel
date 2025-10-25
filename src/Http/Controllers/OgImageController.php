<?php

namespace Petersuhm\Ogkit\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Petersuhm\Ogkit\Support\ImageGenerator;

final class OgImageController extends Controller
{
    public function __invoke(Request $request, ImageGenerator $gen)
    {
        $request->validate(['t' => 'required|string']);
        $token = $this->unpack($request->string('t'));

        $variant = $token['v'] ?? null;
        $render = $token['url'] ?? null;
        $width = (int) ($token['w'] ?? 1200);
        $height = (int) ($token['h'] ?? 630);
        $format = $token['fmt'] ?? 'jpeg';

        abort_unless($variant && $render, 422);

        $disk = config('ogkit.disk', 'local');
        $key  = "ogkit/{$variant}.{$width}x{$height}.{$format}";

        if ($this->shouldCapture($disk, $key)) {
            $tmp = tempnam(sys_get_temp_dir(), 'og_') . ".{$format}";
            $gen->capture($render, $tmp, $width, $height, $format);
            Storage::disk($disk)->put($key, fopen($tmp, 'rb'));
            @unlink($tmp);
        }

        return response()->stream(function () use ($disk, $key) {
            $s = Storage::disk($disk)->readStream($key);
            fpassthru($s);
            fclose($s);
        }, 200, [
            'Content-Type'  => "image/{$format}",
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function shouldCapture(string $disk, string $key): bool
    {
        return ! Storage::disk($disk)->exists($key);
    }

    private function unpack(string $b64url): array
    {
        $pad = strlen($b64url) % 4;
        if ($pad) $b64url .= str_repeat('=', 4 - $pad);
        $json = base64_decode(strtr($b64url, '-_', '+/'), true) ?: '';
        return json_decode($json, true) ?: [];
    }
}
