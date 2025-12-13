<?php

use PXP\Matrix\Matrix;
use PXP\Matrix\Vector;

require __DIR__.'/vendor/autoload.php';

$A = matrix([
    [5, 8, 6, 3],
    [7, 9, 1, 3],
    [2, 6, 8, 7],
    [6, 0, 5, 7],
]);

$B = matrix([
    [4, 6, 8, 3],
    [5, 0, 9, 4],
    [2, 5, 1, 5],
    [1, 4, 5, 0],
]);

$c = vector([1, 5, 7, 4]);

echo join("\n", [
    $A, $B, $c,
    $A->times($B),
    $c->t()->times($A),
    $B->times($c),
    $c->inner($c),
    $c->outer($c),
    $A->plus($B),
    $A->minus($B),
]);
