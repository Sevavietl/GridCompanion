<?php

// function map(callable $func, \Iterator $list)
// {
//     foreach ($list as &$item) {
//         $func($item);
//     }
//
//     return $list;
// }

function reduce(callable $func, $acc, \Iterator $list)
{
    foreach ($list as $item) {
        $acc = $func($item, $acc);
    }

    return $acc;
}

function uncamelize($string)
{
    $regex = '/([a-z0-9\-])([A-Z])/';

    return strtolower(preg_replace($regex, '$1_$2', $string));
}
