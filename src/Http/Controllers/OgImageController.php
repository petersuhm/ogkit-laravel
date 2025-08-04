<?php

namespace Petersuhm\Ogkit\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Petersuhm\Ogkit\Support\ImageGenerator;

final class OgImageController extends Controller
{
    public function __invoke(Request $request, ImageGenerator $generator)
    {
        $request->validate([
            'v' => 'required|string',
            'r' => 'required|string',
            'w' => 'nullable|integer',
            'h' => 'nullable|integer',
            'f' => 'nullable|in:jpeg,png,webp',
        ]);

        $variant = $request->string('v');
        $width = (int) $request->input('w', 1200);
        $height = (int) $request->input('h', 630);
        $format = $request->input('f', 'jpeg');
        $renderUrl = $this->expand($request->string('r'));

        $disk = config('ogkit.disk', 'local');
        $key  = "ogkit/{$variant}.{$width}x{$height}.{$format}";

        // USE SOME SORT OF CALLBACK MAGIC TO CLEAN UP THE TEMP FILE
        if ($this->shouldCapture($disk, $key)) {
            $tmpFile = tempnam(sys_get_temp_dir(), 'og_') . ".{$format}";

            $generator->capture($renderUrl, $tmpFile, $width, $height, $format);

            Storage::disk($disk)->put($key, fopen($tmpFile, 'rb'));

            @unlink($tmpFile);
        }

        // WHY DOES THIS NEED TO STREAM?
        return response()->stream(function () use ($disk, $key) {
            $s = Storage::disk($disk)->readStream($key);
            fpassthru($s);
            fclose($s);
        }, 200, [
            'Content-Type' => "image/{$format}",
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function shouldCapture(string $disk, string $key): bool
    {
        return ! Storage::disk($disk)->exists($key);
    }

    private function expand(string $b64url): string
    {
        $pad = strlen($b64url) % 4;

        if ($pad) {
            $b64url .= str_repeat('=', 4 - $pad);
        }

        return base64_decode(strtr($b64url, '-_', '+/'));
    }
}
