<?php

namespace Itsquad\Pokedex\Api\Service;

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
