<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Api;

use InvalidArgumentException;
use Itsquad\Pokedex\Api\Data\PokemonInterface;

/**
 * Pokemon Repository Interface (Repository Pattern)
 */
interface PokemonRepositoryInterface
{
    /**
     * Get Pokemon by ID
     *
     * @param int $id
     * @return PokemonInterface
     * @throws InvalidArgumentException
     */
    public function getById(int $id): PokemonInterface;
}
