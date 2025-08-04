<?php

namespace Petersuhm\Ogkit\Support;

final class RenderUrlBuilder
{
    public function build(string $path, array $data): string
    {
        $base = rtrim(config('app.url'), '/');
        $query = http_build_query([
            'ogkit-render' => 1,
            'd' => $this->encode($data),
        ]);

        return "{$base}/" . ltrim($path, '/') . '?' . $query;
    }

    private function encode(array $data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);

        return rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
    }
}
