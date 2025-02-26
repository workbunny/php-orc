<?php
declare(strict_types=1);

namespace Workbunny\PhpOrc\Enums;

enum StructRepr: int
{
    case TUPLE = 0;
    case DICT = 1;
}