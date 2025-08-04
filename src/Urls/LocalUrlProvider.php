<?php

namespace Petersuhm\Ogkit\Urls;

final class LocalUrlProvider implements UrlProvider
{
    public function __construct(private string $route = 'ogkit.image') {}

    public function imageUrl(string $renderUrl, string $variant, array $opts = []): string
    {
        $w = $opts['w'] ?? 1200; $h = $opts['h'] ?? 630;

        return route($this->route, [
            'v' => $variant,
            'r' => $this->compact($renderUrl),
            'w' => $w,
            'h' => $h,
            'f' => $opts['fmt'] ?? 'jpeg',
        ]);
    }

    private function compact(string $url): string
    {
        return rtrim(strtr(base64_encode($url), '+/', '-_'), '=');
    }
}
