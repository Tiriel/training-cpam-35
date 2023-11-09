<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Movie\Search\Consumer\OmdbApiConsumer;
use App\Movie\Search\Enum\SearchTypes;
use App\Movie\Search\Mapper\OmdbToMovieMapper;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider implements ProviderInterface
{
    public function __construct(
        private readonly OmdbApiConsumer $consumer,
        private readonly EntityManagerInterface $manager,
        private readonly OmdbToMovieMapper $movieMapper,
        private readonly GenreProvider $provider,
    ) {}

    public function fetchOne(string $value, SearchTypes $type = SearchTypes::Title): Movie
    {
        $data = $this->consumer->fetchMovie($type, $value);

        if (null !== $movie = $this->manager->getRepository(Movie::class)
                ->findOneBy(['title' => $data['Title']])) {
            return $movie;
        }

        $movie = $this->movieMapper->mapValue($data);

        foreach ($this->provider->fetchFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}
