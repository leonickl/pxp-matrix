<?php

namespace LeoNickl\Matrix;

use Exception;

readonly class Vector extends Matrix
{
    public function __construct(array $vector, bool $check = true)
    {
        parent::__construct(array_map(fn($entry) => [$entry], $vector), $check);

        if($this->width() !== 1) {
            throw new Exception('Vector must have width of exactly 1');
        }
    }

    public function inner(Vector $other): Matrix
    {
        return $this->t()->times($other);
    }

    public function outer(Vector $other): Matrix
    {
        return $this->times($other->t());
    }

    public function length(): int
    {
        return $this->height();
    }

    public function sum(): float
    {
        return array_sum($this->col(0));
    }

    public function mean(int $ddof = 0): float
    {
        return $this->sum() / ($this->length() - $ddof);
    }

    public function variance(int $ddof = 0): float
    {
        $mean = $this->mean();
        return $this->map(fn($entry) => ($entry - $mean) ** 2)->vector()->mean($ddof);
    }

    public function variation(): float
    {
        $mean = $this->mean();
        return $this->map(fn($entry) => ($entry - $mean) ** 2)->vector()->sum();
    }
}
