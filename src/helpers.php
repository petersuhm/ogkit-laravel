<?php

use Petersuhm\Ogkit\Facades\OgImage;

if (! function_exists('ogimage')) {
    /**
     * Build an OG image URL.
     *
     * @param string $path
     * @param array $data  e.g. ['v' => 'version-or-hash']
     * @param array $options  e.g. ['w' => 1200, 'h' => 630, 'fmt' => 'jpeg']
     */
    function ogimage(string $path, array $data = [], array $options = []): string
    {
        return OgImage::url($path, $data, $options);
    }
}
