<?php

namespace App\Repositories\Transaction;

use Illuminate\Support\Facades\DB;
use Core\UseCase\Interfaces\TransactionInterface;

class DBTransaction implements TransactionInterface
{
    public function __construct($pdo)
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
