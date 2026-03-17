<?php

declare(strict_types=1);

namespace Itsquad\Pokedex\Controller\Index;

use InvalidArgumentException;
use Itsquad\Pokedex\Api\PokemonRepositoryInterface;
use Itsquad\Pokedex\Block\Pokedex;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;

class Index implements HttpGetActionInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ResultFactory $resultFactory,
        private readonly PokemonRepositoryInterface $pokemonRepository,
    ) {
    }

    public function execute(): Page
    {
        /** @var Page $page */
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->getConfig()->getTitle()->set(__('Pokedex'));

        $pokemonId = (int) $this->request->getParam('pokemon_id');

        if ($pokemonId > 0) {
            try {
                $pokemon = $this->pokemonRepository->getById($pokemonId);
            } catch (InvalidArgumentException) {
                $pokemon = null;
                $error = __('Pokemon with ID %1 not found.', $pokemonId);
            }

            /** @var Pokedex $block */
            $block = $page->getLayout()->getBlock('pokedex');
            if ($block) {
                $block->setPokemon($pokemon ?? null);
                if (isset($error)) {
                    $block->setError((string) $error);
                }
            }
        }

        return $page;
    }
}
