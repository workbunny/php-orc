<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use PyDict;
use PyList;
use PyObject;
use PyStr;
use PyTuple;
use Workbunny\PhpOrc\Converters\ORCConverterClass;
use Workbunny\PhpOrc\Enums\StructRepr;
use Workbunny\PhpOrc\Enums\TypeKind;

/**
 * @property PyObject $schema
 * @property PyObject $compression
 * @property PyTuple $user_metadata
 * @property PyStr $software_version
 * @property PyStr $format_version
 * @property PyStr $writer_id
 * @property PyObject $writer_version
 * @method PyList read(int $num = -1)
 * @method int seek(int $row, int $whence = 0)
 */
#[\PyInherit('Reader', 'pyorc')]
class ReaderClass extends \PyClass
{

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @param string|PyObject $fileo 文件对象
     * @param int $batch_size 批量读取行数
     * @param PyList|array|null $column_indices 按索引获取指定列
     * @param PyList|array|null $column_names 按命名获取指定列
     * @param string $timezone 时区
     * @param int|StructRepr $struct_repr 返回数据的格式 {@link StructRepr}
     * @param PyDict|array<TypeKind, ORCConverterClass>|null $converters
     * @param Predicate|PredicateColumn|null $predicate 注：谓词过滤需要针对orc文件做排序处理，否则会导致过滤不生效
     * @param null $null_value
     */
    public function __construct(
        string|PyObject                $fileo,
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
        $this->attributes['fileo'] = is_string($fileo) ? open($fileo, 'rb') : $fileo;
        $this->attributes['batch_size'] = $batch_size;
        $this->attributes['column_indices'] = $column_indices;
        $this->attributes['column_names'] = $column_names;
        $this->attributes['timezone'] = cls('zoneinfo', 'ZoneInfo', $timezone);
        $this->attributes['struct_repr'] = $struct_repr instanceof StructRepr ? $struct_repr->value : $struct_repr;
        $this->attributes['converters'] = $converters;
        $this->attributes['predicate'] = $predicate ? $predicate() : null;
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
\PyClass::setProxyPath(__DIR__);
