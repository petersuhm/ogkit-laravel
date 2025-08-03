<?php

namespace Petersuhm\Ogkit\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Uri;
use Petersuhm\Ogkit\Capture\ImageCapturer;

class OgImageController extends Controller
{
    public function __invoke(Request $request)
    {
        $file = $this->ogImageFile($request->fullUrl());

        if ($this->shouldCapture($file)) {
            $url = $this->renderUrl($request->get('path'));

            $this->capture($url, $file);
        }

        return response()->file($file, [
            'Content-Type' => 'image/jpeg',
        ]);
    }

    private function capture(string $url, string $file): void
    {
        $this->ensureDirectoryExists($this->ogImagesPath());

        ImageCapturer::capture($url, $file, 1280, 720);
    }

    private function ogImageFile(string $url): string
    {
        return $this->ogImagesPath() . '/ogkit-image-' . sha1($url) . '.jpeg';
    }

    private function ogImagesPath(): string
    {
        return storage_path('app/ogkit-images');
    }

    private function shouldCapture(string $file): bool
    {
        return ! File::exists($file);
    }

    private function renderUrl(string $path): string
    {
        return Uri::of(config('app.url'))
            ->withPath($path)
            ->withQuery(['ogkit-render' => 'true']);
    }

    private function ensureDirectoryExists(string $path): void
    {
        File::ensureDirectoryExists($path);
    }
}
