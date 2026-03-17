<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Test\Unit\Service;

use InvalidArgumentException;
use Itsquad\Pokedex\Api\Client\PokeApiClientInterface;
use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Itsquad\Pokedex\Service\PokeApiService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PokeApiServiceTest extends TestCase
{
    private PokeApiClientInterface|MockObject $pokeApiClientMock;
    private PokeApiService $service;

    protected function setUp(): void
    {
        $this->pokeApiClientMock = $this->createMock(PokeApiClientInterface::class);

        $this->service = new PokeApiService(
            $this->pokeApiClientMock,
        );
    }

    public function testFetchPokemonDataReturnsTransformedData(): void
    {
        $apiResponse = [
            'id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
            'base_experience' => 112,
            'types' => [
                ['slot' => 1, 'type' => ['name' => 'electric', 'url' => 'https://pokeapi.co/api/v2/type/13/']],
            ],
            'sprites' => [
                'front_default' => 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png',
                'back_default' => 'https://example.com/back.png',
            ],
        ];

        $this->pokeApiClientMock->method('getPokemonById')
            ->with(25)
            ->willReturn($apiResponse);

        $result = $this->service->fetchPokemonData(25);

        $this->assertSame(25, $result[PokemonInterface::ID]);
        $this->assertSame('pikachu', $result[PokemonInterface::NAME]);
        $this->assertSame(4, $result[PokemonInterface::HEIGHT]);
        $this->assertSame(60, $result[PokemonInterface::WEIGHT]);
        $this->assertSame(112, $result[PokemonInterface::BASE_EXPERIENCE]);
        $this->assertSame(['electric'], $result[PokemonInterface::TYPES]);
        $this->assertSame(
            'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png',
            $result[PokemonInterface::SPRITE]
        );
    }

    public function testFetchPokemonDataDefaultsBaseExperienceToZero(): void
    {
        $apiResponse = [
            'id' => 1,
            'name' => 'bulbasaur',
            'height' => 7,
            'weight' => 69,
            'types' => [],
            'sprites' => ['front_default' => null],
        ];

        $this->pokeApiClientMock->method('getPokemonById')
            ->with(1)
            ->willReturn($apiResponse);

        $result = $this->service->fetchPokemonData(1);

        $this->assertSame(0, $result[PokemonInterface::BASE_EXPERIENCE]);
        $this->assertSame([], $result[PokemonInterface::TYPES]);
        $this->assertNull($result[PokemonInterface::SPRITE]);
    }

    public function testFetchPokemonDataPropagatesClientException(): void
    {
        $this->pokeApiClientMock->method('getPokemonById')
            ->with(999)
            ->willThrowException(new InvalidArgumentException('Could not fetch Pokemon with ID: 999.'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Could not fetch Pokemon with ID: 999/');

        $this->service->fetchPokemonData(999);
    }
}
