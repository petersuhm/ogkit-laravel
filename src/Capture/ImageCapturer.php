<?php

namespace Petersuhm\Ogkit\Capture;

use Spatie\Browsershot\Browsershot;

class ImageCapturer
{
    public static function capture(string $url, string $file, int $width, int $height)
    {
        return Browsershot::url($url)
            ->waitUntilNetworkIdle()
            ->timeout(30_000)
            ->waitForFunction('window.__OG_READY__ === true')
            ->setScreenshotType('jpeg')
            ->windowSize($width, $height)
            ->fullPage(false)
            ->noSandbox()
            ->save($file);
    }
}
