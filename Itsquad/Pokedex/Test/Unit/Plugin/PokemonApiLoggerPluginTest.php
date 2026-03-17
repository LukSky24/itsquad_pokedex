<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Test\Unit\Plugin;

use Itsquad\Pokedex\Api\PokemonApiInterface;
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
        $result = ['name' => 'pikachu', 'id' => 25];
        $subjectMock = $this->createMock(PokemonApiInterface::class);

        $this->loggerMock->expects($this->once())
            ->method('info')
            ->with('Query: 25 - Result: pikachu');

        $this->plugin->afterFetchPokemonData($subjectMock, $result, 25);
    }

    public function testAfterFetchPokemonDataReturnsResultUnchanged(): void
    {
        $result = [
            'id' => 6,
            'name' => 'charizard',
            'height' => 17,
            'weight' => 905,
            'types' => ['fire', 'flying'],
        ];
        $subjectMock = $this->createMock(PokemonApiInterface::class);

        $returned = $this->plugin->afterFetchPokemonData($subjectMock, $result, 6);

        $this->assertSame($result, $returned);
    }
}
