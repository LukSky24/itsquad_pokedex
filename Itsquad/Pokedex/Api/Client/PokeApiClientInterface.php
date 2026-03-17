<?php

namespace Itsquad\Pokedex\Api\Client;

use InvalidArgumentException;

interface PokeApiClientInterface
{
    /**
     * @param int $id
     * @return array
     * @throws InvalidArgumentException
     */
    public function getPokemonById(int $id): array;
}
