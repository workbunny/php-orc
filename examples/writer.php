<?php

declare(strict_types=1);

use function Workbunny\PhpOrc\open;

require_once __DIR__ . '/../vendor/autoload.php';

$writer = new Workbunny\PhpOrc\Writer(
    open(__DIR__ . '/example-php.orc','wb'),
    "struct<id:int,group:string,name:string,email:string>"
);
$writer->write([
    'id'    => 1,
    'group' => 'workbunny',
    'name'  => 'test1',
    'email' => 'test1@workbunny.com',
]);
$writer->write([
    'id'   => 2,
    'group' => 'workbunny',
    'name'  => 'test2',
    'email' => 'test2@workbunny.com',
]);
$writer->write([
    'id'    => 3,
    'group' => 'workbunny',
    'name'  => 'test3',
    'email' => 'test3@workbunny.com'
]);
$writer->setUserMetadata(group: 'workbunny', user: 'chaz6chez', email: 'chaz6chez1993@outlook.com');
dump(
    $writer->schema()
);
