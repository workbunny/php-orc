<?php
declare(strict_types=1);

namespace Workbunny\PhpOrc\Enums;

enum CompressionStrategy: int
{
    case SPEED = 0;
    case COMPRESSION = 1;
}