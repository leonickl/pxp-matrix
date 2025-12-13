<?php

namespace PXP\Matrix;

use Exception;
use PXP\Matrix\Vector;

readonly class Matrix
{
    private array $columns;
    private array $index;

    public function __construct(
        private array $matrix, 
        bool $check = true, 
        ?array $columns = null, 
        ?array $index = null,
    ) {
        if ($check) {
            $this->check($matrix);
        }

        if($columns) {
            $this->columns($columns);
        }

        if($index) {
            $this->index($index);
        }
    }

    public static function fromFlat(array $elements, int $width)
    {
        return new Matrix(array_chunk($elements, $width));
    }

    private function check(array $matrix): void
    {
        $width = null;

        foreach ($matrix as $row) {
            if ($width === null) {
                $width = count($row);

                continue;
            }

            if ($width !== count($row)) {
                throw new Exception('Matrix rows must have same length');
            }

            foreach($row as $entry) {
                if(! is_int($entry) && ! is_float($entry) && ! is_null($entry)) {
                    throw new Exception('Matrix must contain only int, float, or null');
                }
            }
        }
    }

    public function height(): int
    {
        return count($this->matrix);
    }

    public function width(): int
    {
        return count($this->matrix) === 0 ? 0 : count($this->matrix[0]);
    }

    public function dim(): array
    {
        return [$this->height(), $this->width()];
    }

    public function squared(): bool
    {
        return $this->height() === $this->width();
    }

    public function get(int $row, int $col): int|float|null
    {
        if ($row >= $this->height()) {
            throw new Exception('Row index out of bounds');
        }

        if ($col >= $this->width()) {
            throw new Exception('Col index out of bounds');
        }

        return $this->matrix[$row][$col] ?? null;
    }

    public function without(int $row, int $col): Matrix
    {
        $matrix = [];

        foreach ($this->matrix as $i => $line) {
            if ($i === $row) {
                continue;
            }

            array_splice($line, $col, 1);

            $matrix[] = $line;
        }

        return new Matrix($matrix, check: false);
    }

    public function row(int $i): array
    {
        return $this->matrix[$i];
    }

    public function col(int $j): array
    {
        $col = [];

        foreach ($this->matrix as $row) {
            $col[] = $row[$j];
        }

        return $col;
    }

    public function det(): float
    {
        if (! $this->squared()) {
            throw new Exception('Determinant only defined for square matrices');
        }

        if ($this->width() === 1) {
            return $this->get(0, 0);
        }

        if ($this->width() === 2) {
            return $this->get(0, 0) * $this->get(1, 1)
                - $this->get(0, 1) * $this->get(1, 0);
        }

        // Laplace for first row (row = 0)

        $i = 0;

        $det = 0;

        for ($j = 0; $j < $this->width(); $j++) {
            $det += (-1) ** ($j + $i) * $this->get($i, $j)
                * $this->without($i, $j)->det();
        }

        return $det;
    }

    public function __toString(): string
    {
        $max = 0;

        foreach ($this->matrix as $row) {
            foreach ($row as $element) {
                $max = max($max, strlen((string) $element));
            }
        }

        if(isset($this->columns)) {
            foreach($this->columns as $name) {
                $max = max($max, strlen((string) $name));
            }
        }
        
        if(isset($this->index)) {
            foreach($this->index ?? [] as $name) {
                $max = max($max, strlen((string) $name));
            }
        }

        $string = '';

        if(isset($this->columns)) {
            $line = '';

            foreach($this->columns as $name) {
                $pad = str_repeat(' ', $max - strlen((string) $name));
                $line .= ($line === '' ? (isset($this->index) ? str_repeat(' ', $max + 1) : '') : ' ').$pad.$name;
            }

            $string .= $line;
        }

        foreach ($this->matrix as $i => $row) {
            $line = '';

            if(isset($this->index)) {
                $row = [$this->index[$i], ...$row];
            }

            foreach ($row as $element) {
                $pad = str_repeat(' ', $max - strlen((string) $element));
                $line .= ($line === '' ? '' : ' ').$pad.$element;
            }

            $string .= ($string === '' ? '' : "\n").$line;
        }

        return $string."\n";
    }

    public function transpose(): Matrix
    {
        $matrix = [];

        for ($j = 0; $j < $this->width(); $j++) {
            $line = [];

            for ($i = 0; $i < $this->height(); $i++) {
                $line[] = $this->get($i, $j);
            }

            $matrix[] = $line;
        }

        return new Matrix($matrix, check: false);
    }

    public function t(): Matrix
    {
        return $this->transpose();
    }

    public function invert(): Matrix
    {
        if (! $this->squared()) {
            throw new Exception('Determinant only defined for square matrices');
        }

        $matrix = [];

        foreach ($this->matrix as $i => $row) {
            $line = [];

            foreach ($row as $j => $element) {
                $line[] = (-1) ** ($i + $j) * $this->without($i, $j)->det();
            }

            $matrix[] = $line;
        }

        return new Matrix($matrix, check: false)
            ->transpose()
            ->scalar(1 / $this->det());
    }

    public function scalar(float $scalar)
    {
        return $this->map(fn ($element) => $element * $scalar);
    }

    public function round(int $decimals)
    {
        return $this->map(fn ($element) => round($element, $decimals));
    }

    public function map(callable $action): Matrix
    {
        $matrix = [];

        foreach ($this->matrix as $i => $row) {
            $line = [];

            foreach ($row as $j => $element) {
                $line[] = $action($element);
            }

            $matrix[] = $line;
        }

        return new Matrix($matrix, check: false);
    }

    public function times(Matrix $other): Matrix
    {
        if ($this->width() !== $other->height()) {
            throw new Exception('Can only multiply matrices when width of first is height of second');
        }

        $matrix = [];

        for ($i = 0; $i < $this->height(); $i++) {
            $line = [];

            for ($j = 0; $j < $other->width(); $j++) {
                $row = $this->row($i);
                $col = $other->col($j);

                $sum = 0;

                for ($pos = 0; $pos < count($row); $pos++) {
                    $sum += $row[$pos] * $col[$pos];
                }

                $line[] = $sum;
            }

            $matrix[] = $line;
        }

        return new Matrix($matrix, check: false);
    }

    public function vector(): Vector
    {
        if($this->width() === 1) {
            return new Vector($this->col(0));
        }

        if($this->height() === 1) {
            return new Vector($this->row(0));
        }

        throw new Exception('Matrix is not a vector');
    }

    public function raw(): array
    {
        return $this->matrix;
    }

    public function plus(Matrix $other): Matrix
    {
        if ($this->width() !== $other->width()
            || $this->height() !== $other->height()) {
            throw new Exception('Can only add matrices of same dimensions');
        }

        $matrix = [];

        for ($i = 0; $i < $this->height(); $i++) {
            $line = [];

            for ($j = 0; $j < $this->width(); $j++) {
                $line[] = $this->get($i, $j) + $other->get($i, $j);
            }

            $matrix[] = $line;
        }

        return new Matrix($matrix, check: false);
    }

    public function minus(Matrix $other): Matrix
    {
        return $this->plus($other->scalar(-1));
    }

    public function columns(array $names): Matrix
    {
        if(count($names) !== $this->width()) {
            throw new Exception('Column names have wrong size');
        }

        foreach($names as $name) {
            if(! is_int($name) && ! is_string($name)) {
                throw new Exception('Only int and string is allowed as column name');
            }
        }

        $this->columns = $names;

        return $this;
    }

    public function index(array $names): Matrix
    {
        if(count($names) !== $this->height()) {
            throw new Exception('Index names have wrong size');
        }

        foreach($names as $name) {
            if(! is_int($name) && ! is_string($name)) {
                throw new Exception('Only int and string is allowed as index name');
            }
        }

        $this->index = $names;

        return $this;
    }
}
