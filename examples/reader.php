<?php

declare(strict_types=1);


require_once __DIR__ . '/../vendor/autoload.php';

$reader = new Workbunny\PhpOrc\Reader(__DIR__ . '/example.orc','rb');
// header schema
dump(
    // 总行数
    $reader->count(),
    // 头格式
    $reader->schema(),
    // 压缩类型
    $reader->compression(),
    // 元数据
    $reader->userMetadata(),
    // 写入方式
    $reader->writerId(),
    // 写入版本类型
    $reader->writerVersion(),
    // 基座版本
    $reader->softwareVersion(),
    // 格式版本
    $reader->formatVersion()
);
// 迭代
$reader->iteration(function ($i, $row) {
    dump($i, $row);
});



