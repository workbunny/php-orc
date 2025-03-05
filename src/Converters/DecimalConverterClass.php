<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc\Converters;

#[\PyInherit('DecimalConverter', 'pyorc.converters')]
class DecimalConverterClass extends ORCConverterClass
{
    public function from_orc(mixed ...$args)
    {
        return $this->self()->from_orc(...$args);
    }

    public function to_orc(mixed ...$args)
    {
        return $this->self()->to_orc(...$args);
    }
}
\PyClass::setProxyPath(dirname(__DIR__));
