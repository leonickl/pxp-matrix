<?php

use LeoNickl\Matrix\Vector;
use LeoNickl\Matrix\Matrix;

function matrix(array $matrix, bool $check = true)
{
    return new Matrix($matrix, check: $check);
}

function vector(array $vector)
{
    return new Vector($vector);
}
