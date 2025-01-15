<?php

namespace App\Presenters;

use Core\Domain\Repository\PaginationInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

class PaginationPresenter implements PaginationInterface
{
    public function __construct(private LengthAwarePaginator $paginator) {}

    /**
     * @return stdClass[]
     */
    public function items(): array
    {
        return array_map(
            function ($item) {
                $stdClass = new stdClass();

                foreach ($item as $key => $value) {
                    $stdClass->$key = $value;
                }

                return $stdClass;
            },
            $this->paginator->items()
        );
    }

    public function total(): int
    {
        return $this->paginator->total();
    }

    public function lastPage(): int
    {
        return $this->paginator->lastPage();
    }

    public function firstPage(): int
    {
        return $this->paginator->firstItem();
    }

    public function currentPage(): int
    {
        return $this->paginator->currentPage();
    }

    public function perPage(): int
    {
        return $this->paginator->perPage();
    }
}
