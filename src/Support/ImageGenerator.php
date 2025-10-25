<?php

namespace Petersuhm\Ogkit\Support;

use Petersuhm\Ogkit\Capture\ImageCapturer;

final class ImageGenerator
{
    public function capture(string $url, string $toPath, int $width, int $height, string $format): void
    {
        ImageCapturer::capture($url, $toPath, $width, $height, $format);
    }
}
