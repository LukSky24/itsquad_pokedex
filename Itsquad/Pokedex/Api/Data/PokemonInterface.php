<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Api\Data;

interface PokemonInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id): self;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self;

    /**
     * @return int
     */
    public function getHeight(): int;

    /**
     * @param int $height
     * @return $this
     */
    public function setHeight(int $height): self;

    /**
     * @return int
     */
    public function getWeight(): int;

    /**
     * @param int $weight
     * @return $this
     */
    public function setWeight(int $weight): self;

    /**
     * @return int
     */
    public function getBaseExperience(): int;

    /**
     * @param int $baseExperience
     * @return $this
     */
    public function setBaseExperience(int $baseExperience): self;

    /**
     * @return string[]
     */
    public function getTypes(): array;

    /**
     * @param string[] $types
     * @return $this
     */
    public function setTypes(array $types): self;

    /**
     * @return string|null
     */
    public function getSprite(): ?string;

    /**
     * @param string|null $sprite
     * @return $this
     */
    public function setSprite(?string $sprite): self;
}
