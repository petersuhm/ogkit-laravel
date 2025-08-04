<?php

namespace Petersuhm\Ogkit\Urls;

final class LocalUrlProvider implements UrlProvider
{
    public function __construct(private string $route = 'ogkit.image') {}

    public function imageUrl(string $renderUrl, string $variant, array $options = []): string
    {
        $payload = [
            'v' => $variant,
            'url' => $renderUrl,
            'w' => (int) ($options['w'] ?? 1200),
            'h' => (int) ($options['h'] ?? 630),
            'fmt' => $options['fmt'] ?? 'jpeg',
        ];

        return route($this->route, ['t' => $this->pack($payload)]);
    }

    private function pack(array $payload): string
    {
        ksort($payload);

        $json = json_encode($payload, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        return rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
    }
}
