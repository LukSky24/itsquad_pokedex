<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Model;

use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Itsquad\Pokedex\Api\PokemonApiInterface;
use Itsquad\Pokedex\Api\PokemonRepositoryInterface;

class PokemonRepository implements PokemonRepositoryInterface
{
    public function __construct(
        private readonly PokemonApiInterface $pokemonApi,
        private readonly PokemonFactory $pokemonFactory,
    ) {
    }

    public function getById(int $id): PokemonInterface
    {
        $rawData = $this->pokemonApi->fetchPokemonData($id);

        /** @var Pokemon $pokemon */
        $pokemon = $this->pokemonFactory->create();
        $pokemon->setId($rawData['id'])
            ->setName($rawData['name'])
            ->setHeight($rawData['height'])
            ->setWeight($rawData['weight'])
            ->setBaseExperience($rawData['base_experience'])
            ->setTypes($rawData['types'])
            ->setSprite($rawData['sprite']);

        return $pokemon;
    }
}
