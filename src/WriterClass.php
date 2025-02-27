<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use phpy\PyClass;
use PyCore;
use PyObject;

/**
 * @property PyObject $schema
 * @method void set_user_metadata(PyObject...$kwargs)
 * @method void write(PyObject $object)
 * @method int writerows(PyObject $object)
 * @method void close()
 */
#[\Inherit('Writer', 'pyorc')]
class WriterClass extends PyClass
{

    /**
     * @var array
     */
    protected array $attributes = [];

    public function __construct(
        string|PyObject $fileo,
        string          $schema,
        int             $batch_size = 1024,
        int             $stripe_size = 67108864,
        int             $row_index_stride = 10000,
        int             $compression = 0,
        int             $compression_strategy = 0,
        int             $compression_block_size = 65536,
        ?array          $bloom_filter_columns = null,
        float           $bloom_filter_fpp = 0.05,
        string          $timezone = 'UTC',
        int $struct_repr = 0,
        ?array $converters = null,
        float $padding_tolerance = 0.0,
        float $dict_key_size_threshold = 0.0,
        mixed $null_value = null,
        int $memory_block_size = 65536
    ) {
        $this->attributes['fileo'] = is_string($fileo) ? open($fileo, 'ab') : $fileo;
        $this->attributes['schema'] = PyCore::import('pyorc')->TypeDescription()->from_string($schema);
        $this->attributes['batch_size'] = $batch_size;
        $this->attributes['stripe_size'] = $stripe_size;
        $this->attributes['row_index_stride'] = $row_index_stride;
        $this->attributes['compression'] = $compression;
        $this->attributes['compression_strategy'] = $compression_strategy;
        $this->attributes['compression_block_size'] = $compression_block_size;
        $this->attributes['bloom_filter_columns'] = $bloom_filter_columns;
        $this->attributes['bloom_filter_fpp'] = $bloom_filter_fpp;
        $this->attributes['timezone'] = PyCore::import('zoneinfo')->ZoneInfo($timezone);
        $this->attributes['struct_repr'] = $struct_repr;
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
PyClass::setProxyPath(__DIR__);
