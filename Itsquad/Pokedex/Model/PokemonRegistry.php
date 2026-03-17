<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Model;

use InvalidArgumentException;
use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Itsquad\Pokedex\Api\Service\PokemonApiInterface;
use Itsquad\Pokedex\Model\Data\PokemonFactory;

class PokemonRegistry
{
    private array $objectsRegistryById = [];

    public function __construct(
        private readonly PokemonFactory $pokemonFactory,
        private readonly PokemonApiInterface $pokemonApi,
    ) {
    }

    public function retrieve(int $objectId): PokemonInterface
    {
        if (isset($this->objectsRegistryById[$objectId])) {
            return $this->objectsRegistryById[$objectId];
        }

        $rawData = $this->pokemonApi->fetchPokemonData($objectId);

        $pokemon = $this->pokemonFactory->create();
        $pokemon->setId($rawData[PokemonInterface::ID])
            ->setName($rawData[PokemonInterface::NAME])
            ->setHeight($rawData[PokemonInterface::HEIGHT])
            ->setWeight($rawData[PokemonInterface::WEIGHT])
            ->setBaseExperience($rawData[PokemonInterface::BASE_EXPERIENCE])
            ->setTypes($rawData[PokemonInterface::TYPES])
            ->setSprite($rawData[PokemonInterface::SPRITE]);

        return $this->push($pokemon);
    }

    public function push(PokemonInterface $object): PokemonInterface
    {
        if (!$object->getId()) {
            throw new InvalidArgumentException('Pokemon object must have an ID.');
        }

        $this->objectsRegistryById[$object->getId()] = $object;

        return $object;
    }
}
