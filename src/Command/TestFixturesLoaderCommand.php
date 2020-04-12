<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class TestFixturesLoaderCommand extends Command
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    protected function configure()
    {
        $this
            ->setName('test:fixtures:load')
            ->setDescription('Create DB with data for tests.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force upgrade fixtures');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('force')) {
            $commands = [
                ['command' => 'd:s:d', '--force' => true, '--env' => 'test'],
                ['command' => 'd:s:u', '--force' => true, '--env' => 'test'],
                ['command' => 'h:f:l', '--no-interaction' => true, '--env' => 'test']
            ];

            foreach ($commands as $command) {
                $out = static::runCommand($this->getKernel(), $command);
                $output->writeln($out->fetch());
            }
        }

        $output->writeln('Done.');
        return 0;
    }

    public static function runCommand(KernelInterface $kernel, array $arguments = [])
    {
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput($arguments);
        $output = new BufferedOutput();
        $application->run($input, $output);

        return $output;
    }

    /**
     * @return KernelInterface
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * @param KernelInterface $kernel
     */
    public function setKernel($kernel)
    {
        $this->kernel = $kernel;
    }
}
