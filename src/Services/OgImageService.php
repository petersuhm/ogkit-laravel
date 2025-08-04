<?php

namespace Petersuhm\Ogkit\Services;

use Petersuhm\Ogkit\Support\RenderUrlBuilder;
use Petersuhm\Ogkit\Support\VariantHasher;
use Petersuhm\Ogkit\Urls\UrlProvider;

final class OgImageService
{
    public function __construct(
        private UrlProvider $urls,
        private RenderUrlBuilder $render,
        private VariantHasher $hasher
    ) {}

    public function url(string $path, array $data = [], array $options = []): string
    {
        $renderUrl = $this->render->build($path, $data);
        $variant = $data['v'] ?? $this->hasher->hash($path, $options);

        return $this->urls->imageUrl($renderUrl, $variant, $options);
    }
}
