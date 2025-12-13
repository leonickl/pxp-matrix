<?php

use PXP\Matrix\Vector;
use PXP\Matrix\Matrix;

function matrix(array $matrix, ?array $columns = null, ?array $index = null)
{
    return new Matrix($matrix, columns: $columns, index: $index);
}

function vector(array $vector)
{
    return new Vector($vector);
}
