<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\CartRepository;
use Raketa\BackendTestTask\View\CartView;
use Raketa\BackendTestTask\Infrastructure\Response\JsonResponse;

readonly class GetCartController
{
    public function __construct(
        public CartView $cartView,
        public CartRepository $сartRepository
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $response = new JsonResponse();
        $cart = $this->сartRepository->getCart();

        if (!$cart) {
            $response->getBody()->write(
                json_encode(
                    ['message' => 'Cart not found'],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );

            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus(404);
        }

        $response->getBody()->write(
            json_encode(
                $this->cartView->toArray($cart),
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );
    
        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
