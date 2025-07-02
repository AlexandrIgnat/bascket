<?php

namespace Raketa\BackendTestTask\Repository\Traits;

use Psr\Log\LoggerInterface;

trait Logger
{
      protected LoggerInterface $logger;

      public function setLogger(LoggerInterface $logger)
      {
            $this->logger = $logger;
      }
}