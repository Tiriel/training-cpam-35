<?php

namespace App\Movie\Search\Mapper;

use App\Entity\Genre;

class OmdbToGenreMapper implements MapperInterface
{

    public function mapValue(mixed $value): Genre
    {
        if (!\is_string($value) || \str_contains(', ', $value)) {
            throw new \InvalidArgumentException();
        }

        return (new Genre())->setName($value);
    }
}
