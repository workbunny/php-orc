<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use Closure;
use Countable;
use PyDict;
use PyList;
use PyObject;
use Workbunny\PhpOrc\Converters\ORCConverterClass;
use Workbunny\PhpOrc\Enums\StructRepr;
use Workbunny\PhpOrc\Enums\TypeKind;

class Reader implements Countable
{

    /**
     * @var PyObject|null
     */
    protected null|PyObject $reader;

    /**
     * @param string|PyObject $file
     * @param int $batch_size
     * @param PyList|array|null $column_indices
     * @param PyList|array|null $column_names
     * @param string $timezone
     * @param int|StructRepr $struct_repr
     * @param PyDict|array<TypeKind, ORCConverterClass>|null $converters
     * @param Predicate|PredicateColumn|null $predicate
     * @param null $null_value
     */
    public function __construct(
        string|PyObject                $file,
        int                            $batch_size = 1024,
        null|PyList|array              $column_indices = null,
        null|PyList|array              $column_names = null,
        string                         $timezone = 'UTC',
        int|StructRepr                 $struct_repr = 0,
        null|PyDict|array              $converters = null,
        null|Predicate|PredicateColumn $predicate = null,
                                       $null_value = null
    )
    {
        $this->reader = cls('pyorc', 'Reader',
            fileo: is_string($file) ? open($file, 'rb') : $file,
            batch_size: $batch_size,
            column_indices: $column_indices,
            column_names: $column_names,
            timezone: cls('zoneinfo', 'ZoneInfo', $timezone),
            struct_repr: $struct_repr instanceof StructRepr ? $struct_repr->value : $struct_repr,
            converters: $converters,
            predicate: $predicate ? $predicate() : null,
            null_value: $null_value
        );
    }

    /**
     * @return PyObject|null
     */
    public function getReader(): null|PyObject
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
