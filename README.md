# Matrix

A PHP package incorporating basic matrix algebra.
Examples can be found in `playground.php`.

## Installation

```bash
composer require leonickl/matrix
```

## Usage

Matrices are instances of `Matrix` and can be defined either via the class' constructor or the `matrix()` helper function.


```PHP
// define a matrix
$A = matrix([
    [5, 8, 6, 3],
    [7, 9, 1, 3],
    [2, 6, 8, 7],
    [6, 0, 5, 7],
]);

// invert matrix
$A->invert();

// calculate determinant
$A->det();

// transpose matrix
$A->transpose(); // or ->t()

// multiply with another matrix B
$A->times($B);

// add two matrices
$A->plus($B); // or ->minus($B) for subtraction

// scalar multiplication
$A->scalar(2);
```

A `Matrix` can be converted to a `Vector` object if it has only one column or only one row. A vector is always a matrix of dimension $(n \times 1)$ and offers further methods, while a `Vector` is still treated as a `Matrix`. 
Vectors can be also created instantly by their constructor or the `vector` helper, of course.

```php
// convert a matrix to a vector
$C->vector(); // works if C is of dimension (1⨉n) or (n⨉1)

// define a vector
$c = vector([1, 5, 7, 4]);

// multiply transposed c to A
$c->t()->times($A);

// inner (dot) product of c with itself (c'c)
$c->inner($c);

// outer product of c with itself (cc'), generating a (n⨉n) matrix
$c->outer($c);

// plus, minus, scalar, times work the same
```
