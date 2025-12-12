<?php

use PXP\Matrix\Matrix;

require __DIR__.'/vendor/autoload.php';

$m = new Matrix([
    [5, 8, 6, 3],
    [7, 9, 1, 3],
    [2, 6, 8, 7],
    [6, 0, 5, 7]
]);

echo $m, "\n", $m->det(), "\n\n", $m->invert()->round(2), "\n";
