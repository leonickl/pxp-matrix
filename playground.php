<?php

use PXP\Matrix\Matrix;

require __DIR__.'/vendor/autoload.php';

$A = new Matrix([
    [5, 8, 6, 3],
    [7, 9, 1, 3],
    [2, 6, 8, 7],
    [6, 0, 5, 7],
]);

$B = new Matrix([
    [4, 6, 8, 3],
    [5, 0, 9, 4],
    [2, 5, 1, 5],
    [1, 4, 5, 0],
]);

echo $A, $B, $A->times($B);
