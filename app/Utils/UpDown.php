<?php

namespace App\Utils;

class UpDown
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $this->parseData($data);
    }

    public static function make(array $data)
    {
        return new self($data);
    }

    private function parseData(array $data)
    {
        if (count($data) < 2) {
            $data[] = 0;
        }

        return $data;
    }

    public function get()
    {
        return $this->data;
    }

    public function first()
    {
        return $this->data[0];
    }

    public function last()
    {
        return $this->data[1];
    }

    public function isUp()
    {
        return $this->first() < $this->last();
    }

    public function isDown()
    {
        return ! $this->isUp();
    }

    public function percentage()
    {
        if ($this->first() === 0) {
            return 100;
        }

        return ($this->last() - $this->first()) / $this->first() * 100;
    }

    public function formatPercentage()
    {
        $percentage = number_format($this->percentage());
        $sign = $this->isUp() ? '+' : '-';

        return $sign.$percentage.'%';
    }
}
