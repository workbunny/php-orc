<?php
declare(strict_types=1);

namespace Workbunny\PhpOrc\Enums;

enum CompressionKind: int
{
    case NONE = 0;
    case ZLIB = 1;
    case SNAPPY = 2;
    case LZO = 3;
    case LZ4 = 4;
    case ZSTD = 5;
}

