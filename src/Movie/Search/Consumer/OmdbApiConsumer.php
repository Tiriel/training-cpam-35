<?php

namespace App\Movie\Search\Consumer;

use App\Movie\Search\Enum\SearchTypes;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OmdbApiConsumer
{
    public function fetchMovie(SearchTypes $type, string $value): array
    {
        $data = $this->omdbClient->request(
            'GET',
            '',
            [
                'query' => [
                    'plot' => 'full',
                    $type->value => $value
                ]
            ]
        )->toArray();

        if (array_key_exists('Error', $data) && $data['Error'] === 'Movie not found!') {
            throw new NotFoundHttpException('Movie not found!');
        }

        return $data;
    }
}
