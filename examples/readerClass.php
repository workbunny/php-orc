<?php

declare(strict_types=1);

use function Workbunny\PhpOrc\open;
use function Workbunny\PhpOrc\scalar;
use function Workbunny\PhpOrc\str;

require_once __DIR__ . '/../vendor/autoload.php';

$fileo = open(__DIR__ . '/example-php.orc','rb');

//new \Workbunny\PhpOrc\Converters\DateConverterClass();
//new \Workbunny\PhpOrc\Converters\DecimalConverterClass();
//new \Workbunny\PhpOrc\Converters\TimestampConverterClass();
//dd('ok');

echo "all:\n";
$reader = new Workbunny\PhpOrc\ReaderClass($fileo);

foreach ($reader() as $i => $row) {
    dump(
        "index: $i",
        PyCore::scalar($row)
    );
}
dump(
    'schema: ' . scalar(str($reader->schema)),
    "count: {$reader->count()}"
);

// 读取指定列 - 根据名称
echo "column_names:\n";
$reader = new Workbunny\PhpOrc\ReaderClass($fileo, column_names: ['group', 'timestamp'], struct_repr: StructRepr::DICT->value);

foreach ($reader() as $i => $row) {
    dump(
        "index: $i",
        PyCore::scalar($row)
    );
}

// 读取指定列 - 根据索引
echo "column_indexes:\n";
$reader = new Workbunny\PhpOrc\ReaderClass($fileo, column_indices: [1, 3]);

foreach ($reader() as $i => $row) {
    dump(
        "index: $i",
        PyCore::scalar($row)
    );
}