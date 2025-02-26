<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc\Enums;

enum TypeKind: int
{
    case BOOLEAN = 0;
    case BYTE = 1;
    case SHORT = 2;
    case INT = 3;
    case LONG = 4;
    case FLOAT = 5;
    case DOUBLE = 6;
    case STRING = 7;
    case BINARY = 8;
    case TIMESTAMP = 9;
    case LIST = 10;
    case MAP = 11;
    case STRUCT = 12;
    case UNION = 13;
    case DECIMAL = 14;
    case DATE = 15;
    case VARCHAR = 16;
    case CHAR = 17;
    case TIMESTAMP_INSTANT = 18;
}