<?php

use LeoNickl\Matrix\Matrix;
use LeoNickl\Matrix\Vector;

function matrix(array $matrix, bool $check = true)
{
    return new Matrix($matrix, check: $check);
}

function vector(array $vector, bool $check = true)
{
    return new Vector($vector, check: $check);
}
