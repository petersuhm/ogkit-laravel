<?php

namespace Petersuhm\Ogkit\Urls;

interface UrlProvider
{
    public function imageUrl(string $renderUrl, string $variant, array $opts = []): string;
}
