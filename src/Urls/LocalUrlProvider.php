<?php

namespace Petersuhm\Ogkit\Urls;

final class LocalUrlProvider implements UrlProvider
{
    public function __construct(private string $route = 'ogkit.image') {}

    public function imageUrl(string $renderUrl, string $variant, array $options = []): string
    {
        $width = $options['w'] ?? 1200;
        $height = $options['h'] ?? 630;
        $format = $options['fmt'] ?? 'jpeg';

        return route($this->route, [
            'v' => $variant,
            'r' => $this->compact($renderUrl),
            'w' => $width,
            'h' => $height,
            'f' => $format,
        ]);
    }

    private function compact(string $url): string
    {
        return rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
    }
}
