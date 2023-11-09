<?php

namespace App\Movie\Search\Consumer;

use App\Movie\Search\Enum\SearchTypes;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[When(env: 'prod')]
#[AsDecorator(OmdbApiConsumer::class)]
class CacheableOmdbApiConsumer extends OmdbApiConsumer
{
    public function __construct(
        private readonly OmdbApiConsumer $inner,
        private readonly CacheInterface $cache,
        private readonly SluggerInterface $slugger,
    ) {}

    public function fetchMovie(SearchTypes $type, string $value): array
    {
        $key = $this->slugger->slug(sprintf("%s_%s", $type->value, $value));

        return $this->cache->get(
            $key,
            fn() => $this->inner->fetchMovie($type, $value)
        );
    }
}
