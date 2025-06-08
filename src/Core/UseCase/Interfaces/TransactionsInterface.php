<?php

namespace Core\UseCase\Interfaces;

interface TransactionsInterface
{
    public function commit(): void;
    public function rollback(): void;
}
