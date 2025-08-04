<?php

namespace Petersuhm\Ogkit\Support;

final class RenderUrlBuilder
{
    public function build(string $path): string
    {
        $base = rtrim(config('app.url'), '/');

        return "{$base}/" . ltrim($path, '/') . '?ogkit-render=1';
    }
}
