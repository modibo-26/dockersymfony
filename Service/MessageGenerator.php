<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator {

    public function __construct(private LoggerInterface $logger) {}

    public function getHappyMessage() {
        $this->logger->info('message!');
    }
}