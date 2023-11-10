<?php

namespace App\Movie\Search\Provider;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Search\Consumer\OmdbApiConsumer;
use App\Movie\Search\Enum\SearchTypes;
use App\Movie\Search\Mapper\OmdbToMovieMapper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MovieProvider implements ProviderInterface
{
    private ?SymfonyStyle $io = null;

    public function __construct(
        private readonly OmdbApiConsumer $consumer,
        private readonly EntityManagerInterface $manager,
        private readonly OmdbToMovieMapper $movieMapper,
        private readonly GenreProvider $provider,
        private readonly TokenStorageInterface $storage,
    ) {}

    public function fetchOne(string $value, SearchTypes $type = SearchTypes::Title): Movie
    {
        $data = $this->consumer->fetchMovie($type, $value);
        $this->io?->text('Checking with OMDb...');

        if (null !== $movie = $this->manager->getRepository(Movie::class)
                ->findOneBy(['title' => $data['Title']])) {
            $this->io?->note('Movie already in databse!');

            return $movie;
        }

        $this->io?->text('Building new movie...');
        $movie = $this->movieMapper->mapValue($data);

        foreach ($this->provider->fetchFromOmdbString($data['Genre']) as $genre) {
            $movie->addGenre($genre);
        }

        if (($user = $this->storage->getToken()?->getUser()) instanceof User) {
            $movie->setCreatedBy($user);
        }

        $this->io?->note('Saving in database.');
        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }

    public function setIo(?SymfonyStyle $io): void
    {
        $this->io = $io;
    }
}
