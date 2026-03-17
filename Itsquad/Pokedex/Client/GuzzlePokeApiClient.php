<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Client;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Itsquad\Pokedex\Api\Client\PokeApiClientInterface;
use Magento\Framework\Serialize\Serializer\Json;

class GuzzlePokeApiClient implements PokeApiClientInterface
{
    //Could be moved to config.xml in future
    private const API_URL = 'https://pokeapi.co/api/v2/pokemon/';

    public function __construct(
        private readonly ClientFactory $clientFactory,
        private readonly Json $json,
    ) {
    }

    public function getPokemonById(int $id): array
    {
        try {
            $client = $this->clientFactory->create();
            $response = $client->request('GET', self::API_URL . $id);

            return $this->json->unserialize(
                $response->getBody()->getContents()
            );
        } catch (GuzzleException $e) {
            throw new InvalidArgumentException(
                'Could not fetch Pokemon with ID: ' . $id . '. ' . $e->getMessage()
            );
        }
    }
}
