<?php

namespace App\Movie\Search\Mapper;

interface MapperInterface
{
    public function mapValue(mixed $value): object;
}
