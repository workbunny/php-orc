<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use Exception;
use PyDict;
use PyIter;
use PyObject;
use Workbunny\PhpOrc\Converters\ORCConverterClass;
use Workbunny\PhpOrc\Enums\CompressionKind;
use Workbunny\PhpOrc\Enums\CompressionStrategy;
use Workbunny\PhpOrc\Enums\StructRepr;
use Workbunny\PhpOrc\Enums\TypeKind;

/**
 * @property PyObject $schema
 * @property int current_row
 * @method void set_user_metadata(PyObject...$kwargs)
 * @method void write(PyObject $object)
 * @method int writerows(PyIter|PyObject $object)
 * @method void close()
 */
#[\PyInherit('Writer', 'pyorc')]
class WriterClass extends \PyClass
{

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @param string|PyObject $fileo
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
        string|PyObject         $fileo,
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
        $this->attributes['fileo'] = is_string($fileo) ? open($fileo, 'ab') : $fileo;
        $this->attributes['schema'] = cls('pyorc', 'TypeDescription')->from_string($schema);
        $this->attributes['batch_size'] = $batch_size;
        $this->attributes['stripe_size'] = $stripe_size;
        $this->attributes['row_index_stride'] = $row_index_stride;
        $this->attributes['compression'] = $compression instanceof CompressionKind ? $compression->value : $compression;
        $this->attributes['compression_strategy'] = $compression_strategy instanceof CompressionStrategy ? $compression_strategy->value : $compression_strategy;
        $this->attributes['compression_block_size'] = $compression_block_size;
        $this->attributes['bloom_filter_columns'] = $bloom_filter_columns;
        $this->attributes['bloom_filter_fpp'] = $bloom_filter_fpp;
        $this->attributes['timezone'] = cls('zoneinfo', 'ZoneInfo', $timezone);
        $this->attributes['struct_repr'] = $struct_repr instanceof StructRepr ? $struct_repr->value : $struct_repr;
        $this->attributes['converters'] = $converters;
        $this->attributes['padding_tolerance'] = $padding_tolerance;
        $this->attributes['dict_key_size_threshold'] = $dict_key_size_threshold;
        $this->attributes['null_value'] = $null_value;
        $this->attributes['memory_block_size'] = $memory_block_size;

        parent::__construct();
    }

    public function __init()
    {
        $this->super()->__init__(
            ...$this->attributes
        );
    }

    public function __destruct()
    {
        $this->self()->close();
    }

    /**
     * 返回pyorc.Writer实例
     *
     * @return PyObject|null
     */
    public function __invoke(): ?PyObject
    {
        return $this->self();
    }

    /**
     * 调用pyorc.Writer父类
     *
     * @return PyObject|null
     */
    public function parent(): ?PyObject
    {
        return $this->super();
    }
}

\PyClass::setProxyPath(__DIR__);
