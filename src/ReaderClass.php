<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use phpy\PyClass;
use PyCore;
use PyDict;
use PyList;
use PyObject;

#[\AllowDynamicProperties]
#[\Inherit('Reader', 'pyorc')]
class ReaderClass extends PyClass
{

    protected static array $attributes = [];

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
        self::$attributes['fileo'] = is_string($fileo) ? open($fileo, 'rb') : $fileo;
        self::$attributes['batch_size'] = $batch_size;
        self::$attributes['column_indices'] = $column_indices;
        self::$attributes['column_names'] = $column_names;
        self::$attributes['timezone'] = PyCore::import('zoneinfo')->ZoneInfo($timezone);
        self::$attributes['struct_repr'] = $struct_repr;
        self::$attributes['converters'] = $converters;
        self::$attributes['predicate'] = $predicate;
        self::$attributes['null_value'] = $null_value;
        parent::__construct();
    }

    public function __init()
    {
        $this->super()->__init__(
            ...self::$attributes
        );
    }
}

PyClass::setProxyPath(__DIR__);
