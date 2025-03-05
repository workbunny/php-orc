<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use Exception;
use PyObject;
use PyStr;
use Workbunny\PhpOrc\Enums\TypeKind;

class PredicateColumn
{

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @var PyObject|null
     */
    protected PyObject|null $result = null;

    /**
     * @param int|TypeKind $type_kind
     * @param string|PyStr|null $name
     * @param int|null $index
     * @param int|null $precision
     * @param int|null $scale
     * @throws Exception
     */
    public function __construct(
        int|TypeKind      $type_kind,
        null|string|PyStr $name = null,
        null|int          $index = null,
        null|int          $precision = null,
        null|int          $scale = null
    )
    {
        $this->attributes['type_kind'] = $type_kind instanceof TypeKind ? $type_kind->value : $type_kind;
        $this->attributes['name'] = $name;
        $this->attributes['index'] = $index;
        $this->attributes['precision'] = $precision;
        $this->attributes['scale'] = $scale;
        $this->result = \PyCore::import('pyorc.predicates')->PredicateColumn(
            ...$this->attributes
        );
    }

    public function __invoke(): ?PyObject
    {
        return $this->result;
    }

    public function gt(mixed $value): static
    {
        $this->result = $this->result->__gt__($value);
        return $this;
    }

    public function lt(mixed $value): static
    {
        $this->result = $this->result->__lt__($value);
        return $this;
    }

    public function ge(mixed $value): static
    {
        $this->result = $this->result->__ge__($value);
        return $this;
    }

    public function le(mixed $value): static
    {
        $this->result = $this->result->__le__($value);
        return $this;
    }

    public function eq(mixed $value): static
    {
        $this->result = $this->result->__eq__($value);
        return $this;
    }

    public function ne(mixed $value): static
    {
        $this->result = $this->result->__ne__($value);
        return $this;
    }
}
