<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Test\Unit\Model;

use InvalidArgumentException;
use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Itsquad\Pokedex\Api\Service\PokemonApiInterface;
use Itsquad\Pokedex\Model\Data\Pokemon;
use Itsquad\Pokedex\Model\Data\PokemonFactory;
use Itsquad\Pokedex\Model\PokemonRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PokemonRegistryTest extends TestCase
{
    private PokemonApiInterface|MockObject $pokemonApiMock;
    private PokemonFactory|MockObject $pokemonFactoryMock;
    private PokemonRegistry $registry;

    protected function setUp(): void
    {
        $this->pokemonApiMock = $this->createMock(PokemonApiInterface::class);
        $this->pokemonFactoryMock = $this->createMock(PokemonFactory::class);
        $this->pokemonFactoryMock->method('create')->willReturnCallback(fn() => new Pokemon());

        $this->registry = new PokemonRegistry(
            $this->pokemonFactoryMock,
            $this->pokemonApiMock,
        );
    }

    public function testRetrieveFetchesFromApiOnCacheMiss(): void
    {
        $rawData = [
            PokemonInterface::ID => 25,
            PokemonInterface::NAME => 'pikachu',
            PokemonInterface::HEIGHT => 4,
            PokemonInterface::WEIGHT => 60,
            PokemonInterface::BASE_EXPERIENCE => 112,
            PokemonInterface::TYPES => ['electric'],
            PokemonInterface::SPRITE => 'https://example.com/pikachu.png',
        ];

        $this->pokemonApiMock->expects($this->once())
            ->method('fetchPokemonData')
            ->with(25)
            ->willReturn($rawData);

        $pokemon = $this->registry->retrieve(25);

        $this->assertSame(25, $pokemon->getId());
        $this->assertSame('pikachu', $pokemon->getName());
        $this->assertSame(4, $pokemon->getHeight());
        $this->assertSame(60, $pokemon->getWeight());
        $this->assertSame(112, $pokemon->getBaseExperience());
        $this->assertSame(['electric'], $pokemon->getTypes());
        $this->assertSame('https://example.com/pikachu.png', $pokemon->getSprite());
    }

    public function testRetrieveReturnsCachedOnSecondCall(): void
    {
        $rawData = [
            PokemonInterface::ID => 25,
            PokemonInterface::NAME => 'pikachu',
            PokemonInterface::HEIGHT => 4,
            PokemonInterface::WEIGHT => 60,
            PokemonInterface::BASE_EXPERIENCE => 112,
            PokemonInterface::TYPES => ['electric'],
            PokemonInterface::SPRITE => 'https://example.com/pikachu.png',
        ];

        $this->pokemonApiMock->expects($this->once())
            ->method('fetchPokemonData')
            ->with(25)
            ->willReturn($rawData);

        $first = $this->registry->retrieve(25);
        $second = $this->registry->retrieve(25);

        $this->assertSame($first, $second);
    }

    public function testRetrievePropagatesApiException(): void
    {
        $this->pokemonApiMock->method('fetchPokemonData')
            ->willThrowException(new InvalidArgumentException('Could not fetch Pokemon with ID: 999'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not fetch Pokemon with ID: 999');

        $this->registry->retrieve(999);
    }

    public function testPushAddsToCacheAndRetrieveReturnsCached(): void
    {
        $pokemon = new Pokemon();
        $pokemon->setId(6)
            ->setName('charizard');

        $this->registry->push($pokemon);

        $this->pokemonApiMock->expects($this->never())
            ->method('fetchPokemonData');

        $result = $this->registry->retrieve(6);

        $this->assertSame($pokemon, $result);
    }

    public function testPushThrowsExceptionForPokemonWithoutId(): void
    {
        $pokemon = new Pokemon();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Pokemon object must have an ID.');

        $this->registry->push($pokemon);
    }

}
