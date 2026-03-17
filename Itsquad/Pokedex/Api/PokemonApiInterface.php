<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Api;

use InvalidArgumentException;

interface PokemonApiInterface
{
    /**
     * @param int $id
     * @return array
     * @throws InvalidArgumentException
     */
    public function fetchPokemonData(int $id): array;
}
