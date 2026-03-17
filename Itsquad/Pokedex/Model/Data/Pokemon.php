<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Model\Data;

use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Magento\Framework\DataObject;

class Pokemon extends DataObject implements PokemonInterface
{
    public function getId(): int
    {
        return (int) $this->getData(PokemonInterface::ID);
    }

    public function setId(int $id): PokemonInterface
    {
        return $this->setData(PokemonInterface::ID, $id);
    }

    public function getName(): string
    {
        return (string) $this->getData(PokemonInterface::NAME);
    }

    public function setName(string $name): PokemonInterface
    {
        return $this->setData(PokemonInterface::NAME, $name);
    }

    public function getHeight(): int
    {
        return (int) $this->getData(PokemonInterface::HEIGHT);
    }

    public function setHeight(int $height): PokemonInterface
    {
        return $this->setData(PokemonInterface::HEIGHT, $height);
    }

    public function getWeight(): int
    {
        return (int) $this->getData(PokemonInterface::WEIGHT);
    }

    public function setWeight(int $weight): PokemonInterface
    {
        return $this->setData(PokemonInterface::WEIGHT, $weight);
    }

    public function getBaseExperience(): int
    {
        return (int) $this->getData(PokemonInterface::BASE_EXPERIENCE);
    }

    public function setBaseExperience(int $baseExperience): PokemonInterface
    {
        return $this->setData(PokemonInterface::BASE_EXPERIENCE, $baseExperience);
    }

    public function getTypes(): array
    {
        return (array) $this->getData(PokemonInterface::TYPES);
    }

    public function setTypes(array $types): PokemonInterface
    {
        return $this->setData(PokemonInterface::TYPES, $types);
    }

    public function getSprite(): ?string
    {
        return $this->getData(PokemonInterface::SPRITE);
    }

    public function setSprite(?string $sprite): PokemonInterface
    {
        return $this->setData(PokemonInterface::SPRITE, $sprite);
    }
}
