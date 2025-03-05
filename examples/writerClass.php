<?php

declare(strict_types=1);

use function Workbunny\PhpOrc\bytes;
use function Workbunny\PhpOrc\open;
use function Workbunny\PhpOrc\scalar;
use function Workbunny\PhpOrc\str;

require_once __DIR__ . '/../vendor/autoload.php';

$writer = new Workbunny\PhpOrc\WriterClass(
    open(__DIR__ . '/example-php.orc','wb'),
    "struct<id:int,group:string,name:string,email:string,timestamp:int>"
);
// 普通写入
foreach (range(1, 5) as $i) {
    $writer->write(new PyTuple([
        'id'        => $i,
        'group'     => 'workbunny',
        'name'      => "test-class-write-$i",
        'email'     => "test{$i}@workbunny.com",
        'timestamp' => time()
    ]));
}
// 迭代器写入
$writer->writerows(
    (new Workbunny\PhpOrc\Iterator(function () {
        foreach (range(10, 15) as $i) {
            yield new PyTuple([
                'id'        => $i,
                'group'     => 'workbunny',
                'name'      => "test-class-writerows-$i",
                'email'     => "test{$i}@workbunny.com",
                'timestamp' => time()
            ]);
        }
    }))()
);
// 写入用户元数据
$writer->set_user_metadata(group: bytes('workbunny'), user: bytes('chaz6chez'), email: bytes('chaz6chez1993@outlook.com'));
dump(
    scalar(str($writer->schema))
);
