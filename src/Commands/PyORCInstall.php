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
        // 检查是否已安装Python3
        $pythonExists = $this->exec('command -v python3',  ignore: true);
        if (
            !$pythonExists or
            version_compare(
                $this->pythonVersion = substr($this->exec('python3 --version', ignore: true), 7, 4),
                '3.10',
                '<'
            )
        ) {
            return $this->error('Please run `bin/php-orc install:python` to install Python 3.10+.');
        }

        $currentPath = getcwd();
        $commandPrefix = $this->getInput()?->getOption('venv') ? "$currentPath/.venv/bin/" : '';
        // 检查pip版本并升级（如果需要）
        $this->comment('Upgrading pip ...');
        $pipOutdated = $this->exec( "{$commandPrefix}pip list --outdated", ignore: true);
        if (str_contains($pipOutdated, 'pip ')) {
            $this->execWithProgress("{$commandPrefix}pip install --upgrade pip");
            $this->comment('pip is up-to-date.');
        } else {
            $this->comment('pip is already up-to-date.');
        }

        $this->comment("Installing TZData ...");
        $pyorcInstalled = $this->exec("{$commandPrefix}pip show tzdata", ignore: true);
        if (!$pyorcInstalled) {
            $this->system("{$commandPrefix}pip install tzdata --break-system-packages");
            $this->comment("TZData is installed.");
        } else {
            $this->comment("TZData $version is already installed.");
        }

        // 检查是否已经安装了pyorc
        $this->comment("Installing PyORC-$version ...");
        $pyorcInstalled = $this->exec("{$commandPrefix}pip show pyorc", ignore: true);
        if (!$pyorcInstalled or ($version !== 'latest' and !str_contains($pyorcInstalled, "Version: $version"))) {
            $this->system("{$commandPrefix}pip install pyorc" . ($version === 'latest' ? '' : "==$version") . ' --break-system-packages');
            $this->comment("PyORC $version is installed.");
        } else {
            $this->comment("PyORC $version is already installed.");
        }

        return $this->success('Python and PyORC installation complete.');
    }
}
