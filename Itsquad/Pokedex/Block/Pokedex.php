<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Block;

use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Magento\Framework\View\Element\Template;

class Pokedex extends Template
{
    private ?PokemonInterface $pokemon = null;
    private string $error = '';

    public function setPokemon(?PokemonInterface $pokemon): void
    {
        $this->pokemon = $pokemon;
    }

    public function getPokemon(): ?PokemonInterface
    {
        return $this->pokemon;
    }

    public function setError(string $error): void
    {
        $this->error = $error;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function hasPokemon(): bool
    {
        return $this->pokemon !== null;
    }

    public function getSearchUrl(): string
    {
        return $this->getUrl('pokedex');
    }
}
