<?php

declare(strict_types=1);

namespace Workbunny\PhpOrc\Commands;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class PhpyInstall extends AbstractCommand
{

    /** @inheritdoc  */
    protected function configure(): void
    {
        $this
            ->setName('phpy-install')
            ->setDescription('Installs PHP-ext PHPy.')
            ->addArgument('version', InputArgument::REQUIRED, 'The version of PHPy to install');
    }

    /** @inheritdoc  */
    protected function handler(): int
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $version = $this->getInput()?->getArgument('version');

        // 询问安装目录
        $question = new Question("[?] <comment>Please specify the installation directory (default: .runtime): </comment>\n", getcwd() . '/.runtime');
        $installDir = $helper->ask($this->getInput(), $this->getOutput(), $question) . "/swoole-phpy-$version";

        if (!file_exists($installDir)) {
            // 下载源码
            $this->comment('Downloading the latest source code ...');
            $this->system($version === 'latest' ?
                "git clone --depth 1 https://github.com/swoole/phpy.git $installDir":
                "git clone --depth 1 --branch $version https://github.com/swoole/phpy.git $installDir", $rc);
            if ($rc !== 0) {
                return $this->error('Error downloading source code.');
            }
        } else {
            $this->comment('PHPy source code already downloaded.');
        }

        // 安装编译依赖组件
        $this->comment('Installing dependencies...');
        if ($installCommands = $this->getSystemInstallCommands()) {
            $this->system($installCommands, $rc);
            if ($rc !== 0) {
                return $this->error('Error installing dependencies.');
            }
        }

        // 询问 Python 安装路径
        $question = new Question("[?] <comment>Please specify the Python installation directory (default: /usr):</comment> \n", '/usr');
        $pythonDir = $helper->ask($this->getInput(), $this->getOutput(), $question);

        // 编译并安装拓展
        $this->comment('Building and installing PHPy extension...');
        $this->system('cd ' . $installDir . ' && phpize && ./configure --with-python-dir=' . escapeshellarg($pythonDir) . ' && make && make install', $rc);
        if ($rc !== 0) {
            return $this->error('Error building and installing PHPy extension.');
        }

        // 添加扩展到php.ini
        $this->comment('Adding extension to php.ini...');
        if ($iniPath = php_ini_loaded_file()) {
            file_put_contents($iniPath, 'extension=phpy.so' . PHP_EOL, FILE_APPEND);
        }

        // 询问是否移除源码
        $question = new ConfirmationQuestion("[?] <comment>Do you want to remove the source code? [Y/n]: </comment> \n", true);
        if ($helper->ask($this->getInput(), $this->getOutput(), $question)) {
            $filesystem = new Filesystem();
            $filesystem->remove($installDir);
        }

        // 询问是否卸载编译依赖组件
        $question = new ConfirmationQuestion("[?] <comment>Do you want to remove the build dependencies? [Y/n]: </comment> \n", true);
        if ($helper->ask($this->getInput(), $this->getOutput(), $question)) {
            $removeCommands = $this->getSystemUninstallCommands();
            if ($removeCommands) {
                $this->system($removeCommands, $rc);
                if ($rc !== 0) {
                    return $this->error('Error removing build dependencies.');
                }
            }
        }

        return $this->success('PHPy installation completed successfully.');
    }

    private function getSystemInstallCommands(): ?string
    {
        return match ($this->getSystemType()) {
            'alpine'    => 'apk add --no-cache gcc g++ make autoconf',
            'redhat'    => 'sudo yum install -y gcc gcc-c++ make autoconf',
            'darwin'    => 'brew install autoconf',
            'windows'   => null,
            default     => 'sudo apt-get install -y build-essential autoconf',
        };
    }

    private function getSystemUninstallCommands(): ?string
    {
        return match ($this->getSystemType()) {
            'alpine'    => 'apk del gcc g++ make autoconf',
            'redhat'    => 'sudo yum remove -y gcc gcc-c++ make autoconf',
            'darwin'    => 'brew uninstall autoconf',
            'windows'   => null,
            default     => 'sudo apt-get remove -y build-essential autoconf',
        };
    }

    private function getSystemType(): string
    {
        return match (true) {
            file_exists('/etc/alpine-release')                => 'alpine',
            file_exists('/etc/redhat-release')                => 'redhat',
            stripos(php_uname('s'), 'Darwin') !== false  => 'darwin',
            stripos(php_uname('s'), 'Windows') !== false => 'windows',
            default                                                    => 'linux',
        };
    }
}
