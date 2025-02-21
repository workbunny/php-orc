<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc\Commands;

use Symfony\Component\Console\Input\InputArgument;

class PyORCInstall extends AbstractCommand
{

    /** @inheritdoc  */
    protected function configure(): void
    {
        $this
            ->setName('pyorc-install')
            ->setDescription('Installs Python 3.10+ and the PyORC module.')
            ->addArgument('version', InputArgument::OPTIONAL, 'The version of PyORC to install', 'latest');
    }

    /** @inheritdoc  */
    protected function handler(): int
    {
        $version = $this->getInput()?->getArgument('version');
        $this->comment('Checking and installing Python 3.10+ ...');
        // 检查是否已安装Python3
        $pythonExists = $this->exec('command -v python3',  ignore: true);
        if (
            !$pythonExists or
            version_compare(
                substr($this->exec('python3 --version', ignore: true), 7),
                '3.10',
                '<'
            )
        ) {
            if (!$installCommands = $this->getPythonInstallCommands()) {
                return $this->error('Unsupported operating system.');
            }
            $this->execWithProgress($installCommands, $lastLine);
            if (!str_starts_with($lastLine, 'OK:')) {
                return $this->error('Failed to install Python 3.10+.');
            }
            // 再次检查Python版本
            if (version_compare(substr($this->exec('python3 --version', ignore: true), 7), '3.10', '<')) {
                return $this->error('Python version must be 3.10 or higher.');
            }
            $this->comment('Python 3.10+ is installed.');
        } else {
            $this->comment('Python 3.10+ is already installed.');
        }

        // 检查虚拟环境是否已经存在
        $currentPath = getcwd();
        $this->comment("Setting up virtual environment in $currentPath ...");
        if (!file_exists("$currentPath/.venv")) {
            $this->system("python3 -m venv $currentPath/.venv",ignore: true);
            $this->system("source $currentPath/.venv/bin/activate", ignore: true);
            $this->comment('Virtual environment created.');
        } else {
            $this->comment('Virtual environment already exists.');
        }

        // 检查pip版本并升级（如果需要）
        $this->comment('Upgrading pip ...');
        $pipOutdated = $this->system($currentPath . '/.venv/bin/pip list --outdated', ignore: true);
        if (strpos($pipOutdated, 'pip ')) {
            $this->execWithProgress($currentPath . '/.venv/bin/pip install --upgrade pip');
            $this->comment('pip is up-to-date.');
        } else {
            $this->comment('pip is already up-to-date.');
        }

        // 检查是否已经安装了pyorc
        $this->comment("Installing pyorc version: $version ...");
        $pyorcInstalled = $this->system("$currentPath/.venv/bin/pip show pyorc", ignore: true);
        if (!$pyorcInstalled or ($version !== 'latest' and !str_contains($pyorcInstalled, "Version: $version"))) {
            $this->execWithProgress($currentPath . '/.venv/bin/pip install pyorc' . ($version === 'latest' ? '' : '==' . escapeshellarg($version)));
            $this->comment("PyORC $version is installed.");
        } else {
            $this->comment("PyORC $version is already installed.");
        }

        return $this->success('Python and PyORC installation complete.');
    }

    private function getPythonInstallCommands(): ?string
    {
        return match ($this->getSystemType()) {
            'alpine'    => 'apk add --no-cache python3 py3-pip python3-dev',
            'redhat'    => 'sudo yum install -y python3 python3-venv python3-dev',
            'darwin'    => 'brew install python3',
            'windows'   => null,
            default     => 'sudo apt-get install -y python3 python3-venv python3-dev'
        };
    }

    private function getSystemType(): string
    {
        return match (true) {
            file_exists('/etc/alpine-release')                  => 'alpine',
            file_exists('/etc/redhat-release')                  => 'redhat',
            stripos(php_uname('s'), 'Darwin') !== false    => 'darwin',
            stripos(php_uname('s'), 'Windows') !== false   => 'windows',
            default                                                     => 'linux',
        };
    }
}
