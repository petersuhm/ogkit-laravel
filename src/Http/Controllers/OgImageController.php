<?php

namespace Petersuhm\Ogkit\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Uri;
use Spatie\Browsershot\Browsershot;

class OgImageController extends Controller
{
    public function __invoke(Request $request, Browsershot $browsershot)
    {
        $path = $request->get('path');

        $url = Uri::of(config('app.url'))
            ->withPath($path)
            ->withQuery(['ogkit-render' => 'true']);

        $width = 1280;
        $height = 720;

        $tempPath = tempnam(sys_get_temp_dir(), 'ogkit-images') . '.jpeg';

        $browsershot->setUrl($url)
            ->waitUntilNetworkIdle()
            ->timeout(30_000)
            ->waitForFunction('window.__OG_READY__ === true')
            ->setScreenshotType('jpeg')
            ->windowSize($width, $height)
            ->fullPage(false)
            ->noSandbox()
            ->save($tempPath);

        return response()->file($tempPath, [
            'Content-Type' => 'image/jpeg',
        ]);
    }
}
