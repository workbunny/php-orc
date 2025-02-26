<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use Closure;
use Countable;
use PyCore;
use PyDict;
use PyIter;
use PyList;

class Reader implements Countable
{

    /**
     * @var PyIter|null
     */
    protected null|PyIter $reader;

    /**
     * @param string $file
     * @param string $mode
     * @param int $batch_size
     * @param PyList|null $column_indices
     * @param PyList|null $column_names
     * @param string $timezone
     * @param int $struct_repr
     * @param PyDict|null $converters
     * @param null $predicate
     * @param null $null_value
     */
    public function __construct(
        string  $file,
        string  $mode = 'rb',
        int     $batch_size = 1024,
        ?PyList $column_indices = null,
        ?PyList $column_names = null,
        string  $timezone = 'UTC',
        int     $struct_repr = 0,
        ?PyDict $converters = null,
                $predicate = null,
                $null_value = null
    )
    {

        $this->reader = PyCore::import('pyorc')->Reader(
            open($file, $mode),
            $batch_size,
            $column_indices,
            $column_names,
            PyCore::import('zoneinfo')->ZoneInfo($timezone),
            $struct_repr,
            $converters,
            $predicate,
            $null_value
        );
    }

    /**
     * @return PyIter|null
     */
    public function getReader(): null|PyIter
    {
        return $this->reader;
    }

    /**
     * 迭代
     *
     * @param Closure<int, array> $closure = function ($i, $row) {}
     * @return void
     */
    public function iteration(Closure $closure): void
    {
        foreach ($this->getReader() as $i => $row) {
            call_user_func($closure, $i, scalar($row));
        }
    }

    /**
     * 获取头格式
     *
     * @return string|null
     */
    public function schema(): null|string
    {
        return scalar(str($this->getReader()->schema));
    }

    /**
     * 获取压缩类型
     *
     * @return int
     */
    public function compression(): int
    {
        return (int)scalar(str($this->getReader()->compression));
    }

    /**
     * 获取元数据
     *
     * @return array
     */
    public function userMetadata(): array
    {
        return scalar($this->getReader()->user_metadata);
    }

    /**
     * 获取写入方式
     *
     * @return string
     */
    public function writerId(): string
    {
        return scalar($this->getReader()->writer_id);
    }

    /**
     * 获取写入版本类型
     *
     * @return int
     */
    public function writerVersion(): int
    {
        return (int)scalar(str($this->getReader()->writer_version));
    }

    /**
     * 获取基座版本
     *
     * @return string
     */
    public function softwareVersion(): string
    {
        return scalar($this->getReader()->software_version);
    }

    /**
     * 获取格式版本
     *
     * @return array
     */
    public function formatVersion(): array
    {
        return scalar($this->getReader()->format_version);
    }

    /**
     * 返回总条数
     *
     * @return int
     */
    public function count(): int
    {
        return $this->getReader()->__len__();
    }
}
