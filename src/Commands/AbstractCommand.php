<?php declare(strict_types=1);

namespace Workbunny\PhpOrc\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{

    /**
     * @var OutputInterface|null
     */
    protected ?OutputInterface $output = null;

    /**
     * @var InputInterface|null
     */
    protected ?InputInterface $input = null;

    /**
     * @return OutputInterface|null
     */
    public function getOutput(): ?OutputInterface
    {
        return $this->output;
    }

    /**
     * @return InputInterface|null
     */
    public function getInput(): ?InputInterface
    {
        return $this->input;
    }

    /**
     * 执行命令
     *
     * @param string $command
     * @param mixed|null $output
     * @param int|null $resultCode
     * @param bool $ignore 忽略中断
     * @return string|int|bool|null
     */
    protected function exec(string $command, mixed &$output = null, mixed &$resultCode = 0, bool $ignore = false): string|int|bool|null
    {
        $lastLine = exec($command, $output, $resultCode);
        if ($resultCode !== 0 and !$ignore) {
            $this->error($lastLine);
            return $resultCode;
        }
        return $lastLine;
    }

    /**
     * @param string $command
     * @param int|null $resultCode
     * @param bool $ignore
     * @return string|int|bool|null
     */
    protected function system(string $command, ?int &$resultCode = 0, bool $ignore = false): string|int|bool|null
    {
        $info = system($command, $resultCode);
        if ($resultCode !== 0) {
            $this->error($info);
            if (!$ignore) {
                return $resultCode;
            }
        }
        return $info;
    }

    /**
     * @param string $command
     * @param string|null $lastLine
     * @return void
     */
    protected function execWithProgress(string $command, ?string &$lastLine = null): void
    {
        $process = popen($command, 'r');
        while (!feof($process)) {
            $line = fgets($process);
            if ($line === false) {
                break;
            } else {
                $lastLine = $line;
                $this->output(trim($line));
            }
            usleep(1000);
        }
        pclose($process);
    }

    /**
     * 普通输出
     *
     * @param string $message
     * @return void
     */
    protected function output(string $message): void
    {
        $this->getOutput()?->getFormatter()->setStyle('sub-output', new OutputFormatterStyle('gray', null, ['underscore']));
        $this->getOutput()?->writeln("[>] <sub-output>$message</sub-output>");
    }

    /**
     * 输出info
     *
     * @param string $message
     * @return void
     */
    protected function comment(string $message): void
    {
        $this->getOutput()?->writeln("[i] <comment>$message</comment>");
    }

    /**
     * 输出error
     *
     * @param string $message
     * @return int
     */
    protected function error(string $message): int
    {
        $this->getOutput()?->writeln("[×] <error>$message</error>");
        return self::FAILURE;
    }

    /**
     * 输出success
     *
     * @param string $message
     * @return int
     */
    protected function success(string $message): int
    {
        $this->getOutput()?->writeln("[√] <info>$message</info>");
        return self::SUCCESS;
    }

    /** @inheritdoc  */
    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        return $this->handler();
    }

    /**
     * @return int
     */
    abstract protected function handler(): int;

}