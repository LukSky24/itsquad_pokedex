<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Service;

use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use Itsquad\Pokedex\Api\PokemonApiInterface;
use Magento\Framework\Serialize\Serializer\Json;

class PokeApiService implements PokemonApiInterface
{
    private const API_URL = 'https://pokeapi.co/api/v2/pokemon/';

    public function __construct(
        private readonly ClientFactory $clientFactory,
        private readonly Json $json,
    ) {
    }

    public function fetchPokemonData(int $id): array
    {
        try {
            $client = $this->clientFactory->create();
            $response = $client->request('GET', self::API_URL . $id);
            $body = $response->getBody()->getContents();
            $data = $this->json->unserialize($body);

            return [
                'id' => $data['id'],
                'name' => $data['name'],
                'height' => $data['height'],
                'weight' => $data['weight'],
                'base_experience' => $data['base_experience'] ?? 0,
                'types' => array_map(
                    static fn(array $typeEntry): string => $typeEntry['type']['name'],
                    $data['types'] ?? []
                ),
                'sprite' => $data['sprites']['front_default'] ?? null,
            ];
        } catch (GuzzleException $e) {
            throw new \InvalidArgumentException(
                'Could not fetch Pokemon with ID: ' . $id . '. ' . $e->getMessage()
            );
        }
    }
}
