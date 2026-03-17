<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Test\Unit\Plugin;

use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Itsquad\Pokedex\Api\Service\PokemonApiInterface;
use Itsquad\Pokedex\Plugin\PokemonApiLoggerPlugin;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class PokemonApiLoggerPluginTest extends TestCase
{
    private LoggerInterface $loggerMock;
    private PokemonApiLoggerPlugin $plugin;

    protected function setUp(): void
    {
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->plugin = new PokemonApiLoggerPlugin($this->loggerMock);
    }

    public function testAfterFetchPokemonDataLogsCorrectMessage(): void
    {
        $result = [PokemonInterface::NAME => 'pikachu', PokemonInterface::ID => 25];
        $subjectMock = $this->createMock(PokemonApiInterface::class);

        $this->loggerMock->expects($this->once())
            ->method('info')
            ->with('Query: 25 - Result: pikachu');

        $this->plugin->afterFetchPokemonData($subjectMock, $result, 25);
    }

    public function testAfterFetchPokemonDataReturnsResultUnchanged(): void
    {
        $result = [
            PokemonInterface::ID => 6,
            PokemonInterface::NAME => 'charizard',
            PokemonInterface::HEIGHT => 17,
            PokemonInterface::WEIGHT => 905,
            PokemonInterface::TYPES => ['fire', 'flying'],
        ];
        $subjectMock = $this->createMock(PokemonApiInterface::class);

        $returned = $this->plugin->afterFetchPokemonData($subjectMock, $result, 6);

        $this->assertSame($result, $returned);
    }
}
