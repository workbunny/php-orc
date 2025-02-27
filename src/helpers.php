<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use PyCore;
use PyObject;

/**
 * 打开文件
 *
 * @param string $file
 * @param string $mode
 * @return PyObject
 */
function open(string $file, string $mode = 'r'): PyObject
{
    return PyCore::open($file, $mode);
}

/**
 * to python str
 *
 * @param mixed $value
 * @return mixed
 */
function str(mixed $value)
{
    return PyCore::str($value);
}

/**
 * to python bytes
 *
 * @param mixed $value
 * @return PyObject
 */
function bytes(mixed $value): PyObject
{
    return PyCore::bytes($value);
}

/**
 * python类型->php类型
 *
 * @param mixed $value
 * @return mixed
 */
function scalar(mixed $value): mixed
{
    return PyCore::scalar($value);
}
