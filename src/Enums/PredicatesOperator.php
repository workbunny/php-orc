<?php
declare(strict_types=1);

namespace Workbunny\PhpOrc\Enums;

enum PredicatesOperator: int
{
    case NOT = 0;
    case OR = 1;
    case AND = 2;
    case EQ = 3;
    case LT = 4;
    case LE = 5;
}

