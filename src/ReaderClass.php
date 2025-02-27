<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use phpy\PyClass;
use PyCore;
use PyDict;
use PyList;
use PyObject;
use PyStr;
use PyTuple;

/**
 * @property PyObject $schema
 * @property PyObject $compression
 * @property PyTuple $user_metadata
 * @property PyStr $software_version
 * @property PyStr $format_version
 * @property PyStr $writer_id
 * @property PyObject $writer_version
 */
#[\Inherit('Reader', 'pyorc')]
class ReaderClass extends PyClass
{

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @param string|PyObject $fileo
     * @param int $batch_size
     * @param PyList|null $column_indices
     * @param PyList|null $column_names
     * @param string $timezone
     * @param int $struct_repr
     * @param PyDict|null $converters
     * @param null $predicate
     * @param null $null_value
     * @throws \Exception
     */
    public function __construct(
        string|PyObject $fileo,
        int             $batch_size = 1024,
        ?PyList         $column_indices = null,
        ?PyList         $column_names = null,
        string          $timezone = 'UTC',
        int             $struct_repr = 0,
        ?PyDict         $converters = null,
                        $predicate = null,
                        $null_value = null
    )
    {
        $this->attributes['fileo'] = is_string($fileo) ? open($fileo, 'rb') : $fileo;
        $this->attributes['batch_size'] = $batch_size;
        $this->attributes['column_indices'] = $column_indices;
        $this->attributes['column_names'] = $column_names;
        $this->attributes['timezone'] = PyCore::import('zoneinfo')->ZoneInfo($timezone);
        $this->attributes['struct_repr'] = $struct_repr;
        $this->attributes['converters'] = $converters;
        $this->attributes['predicate'] = $predicate;
        $this->attributes['null_value'] = $null_value;
        parent::__construct();
    }

    public function __init(): void
    {
        $this->super()->__init__(
            ...$this->attributes
        );
    }

    /**
     * 返回pyorc.Reader实例
     *
     * @return PyObject|null
     */
    public function __invoke(): ?PyObject
    {
        return $this->self();
    }

    /**
     * 调用pyorc.Reader父类
     *
     * @return PyObject|null
     */
    public function parent(): ?PyObject
    {
        return $this->super();
    }

    /**
     * @return mixed
     */
    public function count()
    {
        return $this->super()->__len__();
    }
}
PyClass::setProxyPath(__DIR__);
