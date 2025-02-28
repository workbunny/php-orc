<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class PyORCInstall extends AbstractCommand
{
    protected string $pythonVersion = '3.10';

    /** @inheritdoc  */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('install:pyorc')
            ->setDescription('Installs Python PyORC module.')
            ->addArgument('version', InputArgument::OPTIONAL, 'The version of PyORC to install', 'latest')
            ->addOption('venv', null, InputOption::VALUE_NONE, 'Installing in virtual environment');
    }

    /** @inheritdoc  */
    protected function handler(): int
    {
        $version = $this->getInput()?->getArgument('version');
        $this->output('Checking and installing Python PyORC ...');
        // 检查是否已安装Python3
        $pythonExists = $this->exec('command -v python3',  ignore: true);
        $pipExists = $this->exec('command -v pip3',  ignore: true);
        if (
            !$pythonExists or
            !$pipExists or
            version_compare(
                $this->pythonVersion = substr($this->exec('python3 --version', ignore: true), 7, 4),
                '3.10',
                '<'
            )
        ) {
            return $this->error('Please run `./vendor/bin/php-orc install:python` to install Python 3.10+.');
        }

        $currentPath = getcwd();
        $commandPrefix = $this->getInput()?->getOption('venv') ? "$currentPath/.venv/bin/" : '';

        $this->output("Installing TZData ...");
        $pyorcInstalled = $this->exec("{$commandPrefix}pip3 show tzdata", ignore: true);
        if (!$pyorcInstalled) {
            if ($this->execWithProgress("{$commandPrefix}pip3 install tzdata --break-system-packages") !== 0) {
                return $this->error('Error installing TZData.');
            }
        } else {
            $this->comment("TZData $version is already installed.");
        }

        // 检查是否已经安装了pyorc
        $this->output("Installing PyORC-$version ...");
        $pyorcInstalled = $this->exec("{$commandPrefix}pip3 show pyorc", ignore: true);
        if (!$pyorcInstalled or ($version !== 'latest' and !str_contains($pyorcInstalled, "Version: $version"))) {
            if ($this->execWithProgress(
                "{$commandPrefix}pip3 install pyorc" . ($version === 'latest' ? '' : "==$version") . ' --break-system-packages'
            )) {
                return $this->error('Error installing PyORC.');
            }
        } else {
            $this->comment("PyORC $version is already installed.");
        }

        return $this->success('Python and PyORC installation complete.');
    }
}
