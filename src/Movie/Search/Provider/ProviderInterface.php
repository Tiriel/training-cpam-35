<?php

namespace App\Movie\Search\Provider;

interface ProviderInterface
{
    public function fetchOne(string $value): object;
}
