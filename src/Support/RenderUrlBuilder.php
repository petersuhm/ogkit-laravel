<?php

namespace Petersuhm\Ogkit\Support;

use Illuminate\Support\Facades\URL;

final class RenderUrlBuilder
{
    public function __construct(private ?string $routeName = null) {}

    public function build(string $pathOrRoute, array $data): string
    {
        if ($this->routeName) {
            return URL::signedRoute($this->routeName, [
                'path' => ltrim($pathOrRoute, '/'),
                'd' => $this->encode($data),
                'ogkit-render' => 1,
            ]);
        }

        $base = rtrim(config('app.url'), '/');
        $qs = http_build_query(['ogkit-render' => 1, 'd' => $this->encode($data)]);

        return "{$base}/" . ltrim($pathOrRoute, '/') . '?' . $qs;
    }

    private function encode(array $data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        return rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
    }
}
