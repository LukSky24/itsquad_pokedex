<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Test\Unit\Model;

use InvalidArgumentException;
use Itsquad\Pokedex\Api\PokemonApiInterface;
use Itsquad\Pokedex\Model\Pokemon;
use Itsquad\Pokedex\Model\PokemonFactory;
use Itsquad\Pokedex\Model\PokemonRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PokemonRepositoryTest extends TestCase
{
    private PokemonApiInterface|MockObject $pokemonApiMock;
    private PokemonRepository $repository;

    protected function setUp(): void
    {
        $this->pokemonApiMock = $this->createMock(PokemonApiInterface::class);

        $pokemonFactoryMock = $this->createMock(PokemonFactory::class);
        $pokemonFactoryMock->method('create')->willReturn(new Pokemon());

        $this->repository = new PokemonRepository(
            $this->pokemonApiMock,
            $pokemonFactoryMock,
        );
    }

    public function testGetByIdReturnsPokemonWithCorrectData(): void
    {
        $rawData = [
            'id' => 6,
            'name' => 'charizard',
            'height' => 17,
            'weight' => 905,
            'base_experience' => 267,
            'types' => ['fire', 'flying'],
            'sprite' => 'https://example.com/charizard.png',
        ];

        $this->pokemonApiMock->method('fetchPokemonData')
            ->with(6)
            ->willReturn($rawData);

        $pokemon = $this->repository->getById(6);

        $this->assertSame(6, $pokemon->getId());
        $this->assertSame('charizard', $pokemon->getName());
        $this->assertSame(17, $pokemon->getHeight());
        $this->assertSame(905, $pokemon->getWeight());
        $this->assertSame(267, $pokemon->getBaseExperience());
        $this->assertSame(['fire', 'flying'], $pokemon->getTypes());
        $this->assertSame('https://example.com/charizard.png', $pokemon->getSprite());
    }

    public function testGetByIdPropagatesApiException(): void
    {
        $this->pokemonApiMock->method('fetchPokemonData')
            ->willThrowException(new InvalidArgumentException('Could not fetch Pokemon with ID: 999'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Could not fetch Pokemon with ID: 999');

        $this->repository->getById(999);
    }
}
