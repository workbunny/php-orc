<?php

declare(strict_types=1);

use function Workbunny\PhpOrc\bytes;
use function Workbunny\PhpOrc\open;
use function Workbunny\PhpOrc\scalar;
use function Workbunny\PhpOrc\str;

require_once __DIR__ . '/../vendor/autoload.php';

$writer = new Workbunny\PhpOrc\WriterClass(
    open(__DIR__ . '/example-php.orc','wb'),
    "struct<id:int,group:string,name:string,email:string>"
);
$writer->write(new PyTuple([
    'id'    => 1,
    'group' => 'workbunny',
    'name'  => 'test1-class',
    'email' => 'test1@workbunny.com',
]));
$writer->write(new PyTuple([
    'id'   => 2,
    'group' => 'workbunny',
    'name'  => 'test2-class',
    'email' => 'test2@workbunny.com',
]));
$writer->write(new PyTuple([
    'id'    => 3,
    'group' => 'workbunny',
    'name'  => 'test3-class',
    'email' => 'test3@workbunny.com'
]));
$writer->set_user_metadata(group: bytes('workbunny'), user: bytes('chaz6chez'), email: bytes('chaz6chez1993@outlook.com'));
dump(
    scalar(str($writer->schema))
);
