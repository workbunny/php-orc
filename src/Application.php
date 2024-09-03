<?php declare(strict_types=1);

namespace PhpyReqsHelper;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class Application extends \Symfony\Component\Console\Application
{
    const VERSION = 'v0.1.0';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        $this->setName(<<<doc
                      __   __
 _      ______  _____/ /__/ /_  __  ______  ____  __  __
| | /| / / __ \/ ___/ //_/ __ \/ / / / __ \/ __ \/ / / /
| |/ |/ / /_/ / /  / ,< / /_/ / /_/ / / / / / / / /_/ /  <comment>/</comment>
|__/|__/\____/_/  /_/|_/_.___/\__,_/_/ /_/_/ /_/\__, /  <comment>/</comment> phpy-reqs-helper
                                               /____/  <comment>/</comment> 
doc
        );
        $this->setVersion(self::VERSION);
        return parent::doRun($input, $output);
    }
}
