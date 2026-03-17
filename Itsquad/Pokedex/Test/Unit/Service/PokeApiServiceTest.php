<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Test\Unit\Service;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Itsquad\Pokedex\Service\PokeApiService;
use Magento\Framework\Serialize\Serializer\Json;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class PokeApiServiceTest extends TestCase
{
    private ClientFactory|MockObject $clientFactoryMock;
    private Client|MockObject $clientMock;
    private Json|MockObject $jsonMock;
    private PokeApiService $service;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->clientFactoryMock = $this->createMock(ClientFactory::class);
        $this->clientFactoryMock->method('create')->willReturn($this->clientMock);
        $this->jsonMock = $this->createMock(Json::class);

        $this->service = new PokeApiService(
            $this->clientFactoryMock,
            $this->jsonMock,
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

        $this->mockApiResponse($apiResponse);

        $result = $this->service->fetchPokemonData(25);

        $this->assertSame(25, $result['id']);
        $this->assertSame('pikachu', $result['name']);
        $this->assertSame(4, $result['height']);
        $this->assertSame(60, $result['weight']);
        $this->assertSame(112, $result['base_experience']);
        $this->assertSame(['electric'], $result['types']);
        $this->assertSame(
            'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png',
            $result['sprite']
        );
    }

    public function testFetchPokemonDataThrowsOnGuzzleError(): void
    {
        $exception = new class ('Connection refused') extends RuntimeException implements GuzzleException {
        };

        $this->clientMock->method('request')->willThrowException($exception);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Could not fetch Pokemon with ID: 999/');

        $this->service->fetchPokemonData(999);
    }

    private function mockApiResponse(array $data): void
    {
        $streamMock = $this->createMock(StreamInterface::class);
        $streamMock->method('getContents')->willReturn('json_body');

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')->willReturn($streamMock);

        $this->clientMock->method('request')->willReturn($responseMock);
        $this->jsonMock->method('unserialize')->with('json_body')->willReturn($data);
    }
}
