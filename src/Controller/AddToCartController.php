<?php

namespace Raketa\BackendTestTask\Controller;

use Ramsey\Uuid\Uuid;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\View\CartView;
use Raketa\BackendTestTask\Infrastructure\Response\JsonResponse;
use Raketa\BackendTestTask\Repository\CartRepository;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\Repository\CustomerRepository;

readonly class AddToCartController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CustomerRepository $customerRepository,
        private CartView $cartView,
        private CartRepository $cartRepository,
    ) {
    }

    public function create(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        
        try {
            $product = $this->productRepository->getByUuid($rawRequest['productUuid']);
        } catch (\Exception $e) {
            $response = new JsonResponse();
            $response->getBody()->write(
                json_encode(
                    [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
    
            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus(400);
        }
        
        $cart = $this->cartRepository->getCart() ?? null;

        try {
            if (is_null($cart) && $customer = $this->customerRepository->getByUuid($rawRequest['customerUuid'])) {
                $cart = new Cart(
                    Uuid::uuid4()->toString(),
                    $customer,
                    $rawRequest['paymentMethod'],
                    []
                );
            }
          } catch (\Exception $e) {
            $response = new JsonResponse();
            $response->getBody()->write(
                json_encode(
                    [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                )
            );
    
            return $response
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withStatus(400);
        } 
    

        $cart->addItem(new CartItem(
            Uuid::uuid4()->toString(),
            $product->getUuid(),
            $product->getPrice(),
            $rawRequest['quantity'],
        ));

        $this->cartRepository->saveCart($cart);

        $response = new JsonResponse();
        $response->getBody()->write(
            json_encode(
                [
                    'status' => 'success',
                    'cart' => $this->cartView->toArray($cart)
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
