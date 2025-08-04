<?php

namespace Petersuhm\Ogkit\Support;

final class VariantHasher
{
    public function __construct(private string $algo = 'sha1') {}

    public function hash(string $path, array $data, array $opts): string
    {
        $w = $opts['w'] ?? 1200; $h = $opts['h'] ?? 630; $fmt = $opts['fmt'] ?? 'jpeg';

        $payload = [
            'p' => ltrim($path, '/'),
            'd' => $this->normalize($data),
            'w' => (int) $w,
            'h' => (int) $h,
            'f' => $fmt,
        ];

        return hash($this->algo, json_encode($payload, JSON_UNESCAPED_SLASHES));
    }

    private function normalize(array $data): array
    {
        ksort($data);

        return $data;
    }
}
