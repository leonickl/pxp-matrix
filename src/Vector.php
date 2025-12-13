<?php

namespace PXP\Matrix;

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
}
