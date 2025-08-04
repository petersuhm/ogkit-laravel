<?php

namespace Petersuhm\Ogkit\Support;

use Petersuhm\Ogkit\Capture\ImageCapturer;

final class ImageGenerator
{
    public function capture(string $url, string $toPath, int $w, int $h, string $fmt): void
    {
        ImageCapturer::capture($url, $toPath, $w, $h);
    }
}
