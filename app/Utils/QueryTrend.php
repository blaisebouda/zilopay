<?php

namespace App\Utils;

use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class QueryTrend
{
    private  $model;
    private Trend $query;

    public function __construct($model)
    {
        $this->model = $model;
        $this->query = Trend::model($this->model);
    }

    public static function make($model)
    {
        return new self($model);
    }

    public function lastMonths(int $months = 1): self
    {
        $this->query->between(
            start: now()->subMonths($months)->startOfMonth(),
            end: now()->endOfMonth(),
        )->perMonth();

        return $this;
    }


    public function sum(string $column): QueryTrendResult
    {
        return new QueryTrendResult($this->query->sum($column));
    }

    public function count(): QueryTrendResult
    {;

        return new QueryTrendResult($this->query->count());
    }
}

class QueryTrendResult
{
    public function __construct(
        public Collection $data,

    ) {}

    public function values()
    {
        return $this->data
            ->map(fn(TrendValue $value) => $value->aggregate)
            ->toArray();
    }


    public function labels()
    {
        return $this->data
            ->map(fn(TrendValue $value) => $value->date)
            ->toArray();
    }
}
