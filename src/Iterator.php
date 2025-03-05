<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc;

use Closure;
use Exception;
use Generator;

#[\PyInherit('Iterator', 'typing')]
class Iterator extends \PyClass
{
    protected Generator $generator;

    /**
     * @param Generator|Closure $generator
     * @throws Exception
     */
    public function __construct(
        Generator|Closure $generator
    )
    {
        parent::__construct();
        if ($generator instanceof Closure) {
            $generator = $generator();
            if (!$generator instanceof Generator) {
                throw new \InvalidArgumentException('Closure must return a Generator. ');
            }
        }
        $this->generator = $generator;
    }

    public function __iter__()
    {
        return $this->self();
    }

    public function __next__()
    {
        if (!$this->generator->valid()) {
            return null;
        }
        $value = $this->generator->current();
        $this->generator->next();
        return $value;
    }

    public function __invoke(): ?\PyIter
    {
        $res = $this->self();
        return $res instanceof \PyIter ? $res : null;
    }
}
\PyClass::setProxyPath(__DIR__);
