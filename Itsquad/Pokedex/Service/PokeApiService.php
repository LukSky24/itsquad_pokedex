<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Service;

use Itsquad\Pokedex\Api\Client\PokeApiClientInterface;
use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Itsquad\Pokedex\Api\Service\PokemonApiInterface;

class PokeApiService implements PokemonApiInterface
{
    public function __construct(
        private readonly PokeApiClientInterface $pokeApiClient,
    ) {
    }

    public function fetchPokemonData(int $id): array
    {
        $data = $this->pokeApiClient->getPokemonById($id);

        return [
            PokemonInterface::ID => $data['id'],
            PokemonInterface::NAME => $data['name'],
            PokemonInterface::HEIGHT => $data['height'],
            PokemonInterface::WEIGHT => $data['weight'],
            PokemonInterface::BASE_EXPERIENCE => $data['base_experience'] ?? 0,
            PokemonInterface::TYPES => array_map(
                static fn(array $typeEntry): string => $typeEntry['type']['name'],
                $data['types'] ?? []
            ),
            PokemonInterface::SPRITE => $data['sprites']['front_default'] ?? null,
        ];
    }
}
