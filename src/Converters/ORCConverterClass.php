<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc\Converters;

#[\PyInherit('ORCConverter', 'pyorc')]
abstract class ORCConverterClass extends \PyClass
{
    public function __construct()
    {
        parent::__construct();
    }

    abstract public function from_orc(mixed ...$args);

    abstract public function to_orc(mixed ...$args);
}
\PyClass::setProxyPath(getcwd() . '/.runtime');
