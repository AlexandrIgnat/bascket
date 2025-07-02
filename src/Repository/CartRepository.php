<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;
use Raketa\BackendTestTask\Repository\Traits\Logger;

class CartRepository extends ConnectorFacade
{
    use Logger;
    public LoggerInterface $logger;

    public function __construct(string $host, int $port, ?string $password)
    {
        parent::__construct($host, $port, $password, 1);
        parent::build();
    }

    public function saveCart(Cart $cart): void
    {
        try {
            $this->connector->set(session_id(), $cart);
        } catch (Exception $e) {
            $this->logger->error($e);
        }
    }

    public function getCart(): ?Cart
    {
        try {
            $sessionId = session_id();
            if ($this->connector->has($sessionId)) {
                return $this->connector->get($sessionId);
            }
        } catch (Exception $e) {
            $this->logger->error($e);
        }
    }
}
