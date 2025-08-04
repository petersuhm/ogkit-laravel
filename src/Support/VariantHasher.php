<?php

namespace Petersuhm\Ogkit\Support;

final class VariantHasher
{
    public function hash(string $path, array $options): string
    {
        $width = (int) ($options['w'] ?? 1200);
        $height = (int) ($options['h'] ?? 630);
        $format = $options['fmt'] ?? 'jpeg';

        return sha1(json_encode([
            'p' => ltrim($path, '/'),
            'w' => $width,
            'h' => $height,
            'f' => $format,
        ]));
    }
}
