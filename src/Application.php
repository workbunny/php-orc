<?php declare(strict_types=1);

namespace PhpyReqsHelper;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class Application extends \Symfony\Component\Console\Application
{

    const NAME = 'phpy-reqs-helper';
    const VERSION = 'v0.1.0';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Throwable
     */
    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        $this->setVersion(self::VERSION);
        $this->setName(self::NAME);
        $output->writeln(<<<doc
                       __   __                           
  _      ______  _____/ /__/ /_  __  ______  ____  __  __
 | | /| / / __ \/ ___/ //_/ __ \/ / / / __ \/ __ \/ / / /
 | |/ |/ / /_/ / /  / ,< / /_/ / /_/ / / / / / / / /_/ / 
 |__/|__/\____/_/  /_/|_/_.___/\__,_/_/ /_/_/ /_/\__, /  
                                                /____/  
doc
);
        return parent::doRun($input, $output);
    }
}
