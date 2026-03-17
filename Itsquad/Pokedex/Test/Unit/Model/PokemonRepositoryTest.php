<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Test\Unit\Model;

use InvalidArgumentException;
use Itsquad\Pokedex\Model\Data\Pokemon;
use Itsquad\Pokedex\Model\PokemonRegistry;
use Itsquad\Pokedex\Model\PokemonRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PokemonRepositoryTest extends TestCase
{
    private PokemonRegistry|MockObject $pokemonRegistryMock;
    private PokemonRepository $repository;

    protected function setUp(): void
    {
        $this->pokemonRegistryMock = $this->createMock(PokemonRegistry::class);

        $this->repository = new PokemonRepository(
            $this->pokemonRegistryMock,
        );
    }

    public function testGetByIdDelegatesToRegistry(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setId(6)
            ->setName('charizard')
            ->setHeight(17)
            ->setWeight(905)
            ->setBaseExperience(267)
            ->setTypes(['fire', 'flying'])
            ->setSprite('https://example.com/charizard.png');

        $this->pokemonRegistryMock->expects($this->once())
            ->method('retrieve')
            ->with(6)
            ->willReturn($pokemon);

        $result = $this->repository->getById(6);

        $this->assertSame(6, $result->getId());
        $this->assertSame('charizard', $result->getName());
        $this->assertSame(17, $result->getHeight());
        $this->assertSame(905, $result->getWeight());
        $this->assertSame(267, $result->getBaseExperience());
        $this->assertSame(['fire', 'flying'], $result->getTypes());
        $this->assertSame('https://example.com/charizard.png', $result->getSprite());
    }

    public function testGetByIdPropagatesRegistryException(): void
    {
        $this->pokemonRegistryMock->method('retrieve')
            ->willThrowException(new InvalidArgumentException('Could not fetch Pokemon with ID: 999'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not fetch Pokemon with ID: 999');

        $this->repository->getById(999);
    }
}
