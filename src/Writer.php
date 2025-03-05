<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use PyCore;
use PyDict;
use PyObject;
use Workbunny\PhpOrc\Converters\ORCConverterClass;
use Workbunny\PhpOrc\Enums\CompressionKind;
use Workbunny\PhpOrc\Enums\CompressionStrategy;
use Workbunny\PhpOrc\Enums\StructRepr;
use Workbunny\PhpOrc\Enums\TypeKind;

class Writer
{

    /**
     * @var PyObject|null
     */
    protected null|PyObject $writer;

    /**
     * @param string|PyObject $file
     * @param string $schema
     * @param int $batch_size
     * @param int $stripe_size
     * @param int $row_index_stride
     * @param int|CompressionKind $compression
     * @param int|CompressionStrategy $compression_strategy
     * @param int $compression_block_size
     * @param array|null $bloom_filter_columns
     * @param float $bloom_filter_fpp
     * @param string $timezone
     * @param int|StructRepr $struct_repr
     * @param PyDict|array<TypeKind, ORCConverterClass>|null $converters
     * @param float $padding_tolerance
     * @param float $dict_key_size_threshold
     * @param mixed|null $null_value
     * @param int $memory_block_size
     */
    public function __construct(
        string|PyObject         $file,
        string                  $schema,
        int                     $batch_size = 1024,
        int                     $stripe_size = 67108864,
        int                     $row_index_stride = 10000,
        int|CompressionKind     $compression = 0,
        int|CompressionStrategy $compression_strategy = 0,
        int                     $compression_block_size = 65536,
        ?array                  $bloom_filter_columns = null,
        float                   $bloom_filter_fpp = 0.05,
        string                  $timezone = 'UTC',
        int|StructRepr          $struct_repr = 0,
        null|PyDict|array       $converters = null,
        float                   $padding_tolerance = 0.0,
        float                   $dict_key_size_threshold = 0.0,
        mixed                   $null_value = null,
        int                     $memory_block_size = 65536
    )
    {
        $this->writer = cls('pyorc', 'Writer',
            fileo: is_string($file) ? open($file, 'wb') : $file,
            schema: cls('pyorc', 'TypeDescription')->from_string($schema),
            batch_size: $batch_size,
            stripe_size: $stripe_size,
            row_index_stride: $row_index_stride,
            compression: $compression instanceof CompressionKind ? $compression->value : $compression,
            compression_strategy: $compression_strategy instanceof CompressionStrategy ? $compression_strategy->value : $compression_strategy,
            compression_block_size: $compression_block_size,
            bloom_filter_columns: $bloom_filter_columns,
            bloom_filter_fpp: $bloom_filter_fpp,
            timezone: cls('zoneinfo', 'ZoneInfo', $timezone),
            struct_repr: $struct_repr instanceof StructRepr ? $struct_repr->value : $struct_repr,
            converters: $converters,
            padding_tolerance: $padding_tolerance,
            dict_key_size_threshold: $dict_key_size_threshold,
            null_value: $null_value,
            memory_block_size: $memory_block_size
        );
    }

    public function __destruct()
    {
        $this->getWriter()->close();
    }

    /**
     * @return PyObject
     */
    public function getWriter(): PyObject
    {
        return $this->writer;
    }

    /**
     * 获取头格式
     *
     * @return string|null
     */
    public function schema(): null|string
    {
        return scalar(str($this->getWriter()->schema));
    }

    /**
     * 写入
     *
     * @param array $row
     * @return void
     */
    public function write(array $row): void
    {
        if ($row) {
            $this->getWriter()->write(new \PyTuple($row));
        }
    }

    /**
     * 设置元数据
     *
     * @param ...$kwargs
     * @return void
     */
    public function setUserMetadata(...$kwargs): void
    {
        if ($kwargs) {
            foreach ($kwargs as $key => &$value) {
                $value = PyCore::bytes($value);
            }
            $this->getWriter()->set_user_metadata(...$kwargs);
        }
    }
}
