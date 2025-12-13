<?php

use PXP\Matrix\Vector;
use PXP\Matrix\Matrix;

function matrix(array $matrix)
{
    return new Matrix($matrix);
}

function vector(array $vector)
{
    return new Vector($vector);
}
