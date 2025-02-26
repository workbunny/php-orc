<?php
declare(strict_types=1);

namespace Workbunny\PhpOrc\Enums;

enum WriterVersion: int
{
    case ORIGINAL = 0;
    case HIVE_8732 = 1;
    case HIVE_4243 = 2;
    case HIVE_12055 = 3;
    case HIVE_13083 = 4;
    case ORC_101 = 5;
    case ORC_135 = 6;
    case ORC_517 = 7;
    case ORC_203 = 8;
    case ORC_14 = 9;
}