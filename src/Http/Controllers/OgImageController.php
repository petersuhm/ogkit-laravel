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
        $request->validate([
            'v' => 'required|string',
            'r' => 'required|string',
            'w' => 'nullable|integer',
            'h' => 'nullable|integer',
            'f' => 'nullable|in:jpeg,png,webp',
        ]);

        $variant = $request->string('v');
        $w = (int) $request->input('w', 1200);
        $h = (int) $request->input('h', 630);
        $fmt = $request->input('f', 'jpeg');
        $renderUrl = $this->expand($request->string('r'));

        $disk = config('ogkit.disk', 'local');
        $key  = "ogkit/{$variant}.{$w}x{$h}.{$fmt}";

        if (! Storage::disk($disk)->exists($key)) {
            $tmp = tempnam(sys_get_temp_dir(), 'og_') . ".{$fmt}";
            $gen->capture($renderUrl, $tmp, $w, $h, $fmt);
            Storage::disk($disk)->put($key, fopen($tmp, 'rb'));
            @unlink($tmp);
        }

        return response()->stream(function () use ($disk, $key) {
            $s = Storage::disk($disk)->readStream($key);
            fpassthru($s); fclose($s);
        }, 200, [
            'Content-Type' => "image/{$fmt}",
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function expand(string $b64url): string
    {
        $pad = strlen($b64url) % 4;
        if ($pad) $b64url .= str_repeat('=', 4 - $pad);

        return base64_decode(strtr($b64url, '-_', '+/'));
    }
}
