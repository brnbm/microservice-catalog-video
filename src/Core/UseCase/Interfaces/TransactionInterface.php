<?php

namespace Core\UseCase\Interfaces;

interface TransactionInterface
{
    public function commit(): void;
    public function rollback(): void;
}
