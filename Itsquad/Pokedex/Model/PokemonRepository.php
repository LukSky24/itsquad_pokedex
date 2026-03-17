<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Model;

use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Itsquad\Pokedex\Api\PokemonRepositoryInterface;

class PokemonRepository implements PokemonRepositoryInterface
{
    public function __construct(
        private readonly PokemonRegistry $pokemonRegistry,
    ) {
    }

    public function getById(int $id): PokemonInterface
    {
        return $this->pokemonRegistry->retrieve($id);
    }
}
