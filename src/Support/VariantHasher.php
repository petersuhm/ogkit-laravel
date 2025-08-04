<?php

namespace Petersuhm\Ogkit\Support;

final class VariantHasher
{
    public function hash(string $path, array $data, array $options): string
    {
        $width = $options['w'] ?? 1200;
        $height = $options['h'] ?? 630;
        $format = $options['fmt'] ?? 'jpeg';

        $payload = [
            'p' => ltrim($path, '/'),
            'd' => $this->normalize($data),
            'w' => (int) $width,
            'h' => (int) $height,
            'f' => $format,
        ];

        return hash('sha1', json_encode($payload, JSON_UNESCAPED_SLASHES));
    }

    private function normalize(array $data): array
    {
        ksort($data);

        return $data;
    }
}
