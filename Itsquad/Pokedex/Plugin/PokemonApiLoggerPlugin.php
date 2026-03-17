<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Plugin;

use Itsquad\Pokedex\Api\Data\PokemonInterface;
use Itsquad\Pokedex\Api\PokemonApiInterface;
use Psr\Log\LoggerInterface;

class  PokemonApiLoggerPlugin
{
    public function __construct(
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * @param PokemonApiInterface $subject
     * @param array $result
     * @param int $id
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterFetchPokemonData(PokemonApiInterface $subject, array $result, int $id): array
    {
        $this->logger->info(
            sprintf('Query: %d - Result: %s', $id, $result[PokemonInterface::NAME] ?? 'unknown')
        );

        return $result;
    }
}
