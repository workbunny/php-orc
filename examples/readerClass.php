<?php

declare(strict_types=1);

use function Workbunny\PhpOrc\open;
use function Workbunny\PhpOrc\scalar;
use function Workbunny\PhpOrc\str;

require_once __DIR__ . '/../vendor/autoload.php';

$reader = new Workbunny\PhpOrc\ReaderClass(open(__DIR__ . '/example-php.orc','rb'));

foreach ($reader() as $i => $row) {
    dump($i, PyCore::scalar($row));
}

dump(scalar(str($reader->schema)));
