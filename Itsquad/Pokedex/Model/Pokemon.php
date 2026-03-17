<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Model;

use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Magento\Framework\DataObject;

class Pokemon extends DataObject implements PokemonInterface
{
    public function getId(): int
    {
        return (int) $this->getData('id');
    }

    public function setId(int $id): PokemonInterface
    {
        return $this->setData('id', $id);
    }

    public function getName(): string
    {
        return (string) $this->getData('name');
    }

    public function setName(string $name): PokemonInterface
    {
        return $this->setData('name', $name);
    }

    public function getHeight(): int
    {
        return (int) $this->getData('height');
    }

    public function setHeight(int $height): PokemonInterface
    {
        return $this->setData('height', $height);
    }

    public function getWeight(): int
    {
        return (int) $this->getData('weight');
    }

    public function setWeight(int $weight): PokemonInterface
    {
        return $this->setData('weight', $weight);
    }

    public function getBaseExperience(): int
    {
        return (int) $this->getData('base_experience');
    }

    public function setBaseExperience(int $baseExperience): PokemonInterface
    {
        return $this->setData('base_experience', $baseExperience);
    }

    public function getTypes(): array
    {
        return (array) $this->getData('types');
    }

    public function setTypes(array $types): PokemonInterface
    {
        return $this->setData('types', $types);
    }

    public function getSprite(): ?string
    {
        return $this->getData('sprite');
    }

    public function setSprite(?string $sprite): PokemonInterface
    {
        return $this->setData('sprite', $sprite);
    }
}
