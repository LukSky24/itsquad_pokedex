<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Test\Unit\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;
use Itsquad\Pokedex\Client\GuzzlePokeApiClient;
use Magento\Framework\Serialize\Serializer\Json;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class GuzzlePokeApiClientTest extends TestCase
{
    private ClientFactory|MockObject $clientFactoryMock;
    private Client|MockObject $clientMock;
    private Json|MockObject $jsonMock;
    private GuzzlePokeApiClient $client;

    protected function setUp(): void
    {
        $this->clientMock = $this->createMock(Client::class);
        $this->clientFactoryMock = $this->createMock(ClientFactory::class);
        $this->clientFactoryMock->method('create')->willReturn($this->clientMock);
        $this->jsonMock = $this->createMock(Json::class);

        $this->client = new GuzzlePokeApiClient(
            $this->clientFactoryMock,
            $this->jsonMock,
        );
    }

    public function testGetPokemonByIdReturnsDeserializedResponse(): void
    {
        $expectedData = [
            'id' => 25,
            'name' => 'pikachu',
            'height' => 4,
            'weight' => 60,
        ];

        $this->mockApiResponse($expectedData);

        $result = $this->client->getPokemonById(25);

        $this->assertSame($expectedData, $result);
    }

    public function testGetPokemonByIdThrowsOnGuzzleError(): void
    {
        $exception = new class ('Connection refused') extends RuntimeException implements GuzzleException {
        };

        $this->clientMock->method('request')->willThrowException($exception);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Could not fetch Pokemon with ID: 999/');

        $this->client->getPokemonById(999);
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
