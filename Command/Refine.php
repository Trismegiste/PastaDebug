<?php

/*
 * Mondrian
 */

namespace Trismegiste\PastaDebug\Command;

require_once 'PHPUnit/Autoload.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Trismegiste\Mondrian\Builder\Linking;
use Trismegiste\Mondrian\Builder\Statement\Builder;
use Trismegiste\PastaDebug\Builder\AutoloaderBuilder;
use Symfony\Component\Finder\Finder;
use Trismegiste\Mondrian\Config\Helper;
use Symfony\Component\Yaml\Yaml;
use Trismegiste\PastaDebug\PhpUnit;

/**
 * Refine is a refining tool for method calls. 
 * 
 * It refines the config .mondrian.yml with the help of PHPUnit tests of the package
 * 
 * Highly experimental
 */
class Refine extends Command
{

    protected $fineTuning;
    protected $phpfinder;
    protected $newConfigFile;
    protected $dryRunning = false;

    protected function configure()
    {
        $this->setName('typehint:refine')
                ->setDescription('Refines the Mondrian config file for a package')
                ->addArgument('dir', InputArgument::REQUIRED, 'The directory to explore')
                ->addOption('ignore', 'i', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Directories to ignore', array('Tests', 'vendor'))
                ->addOption('dry', null, InputOption::VALUE_NONE, 'Dry run (no write)');
    }

    protected function getConfig($dir)
    {
        $helper = new Helper();

        return $helper->getGraphConfig($dir);
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('dir');
        $ignoreDir = $input->getOption('ignore');
        $this->fineTuning = $this->getConfig($directory);
        $this->phpfinder = $this->getPhpFinder($directory, $ignoreDir);
        $this->newConfigFile = $directory . '/.mondrian.yml';
        $this->dryRunning = $input->getOption('dry');
    }

    protected function getPhpFinder($directory, $ignoreDir)
    {
        $scan = new Finder();
        $scan->files()
                ->in($directory)
                ->name('*.php')
                ->exclude($ignoreDir);

        return $scan;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $classMap = new \ArrayObject();
        $compil = new Linking(new Builder(), new AutoloaderBuilder($classMap));

        $output->writeln(sprintf("Parsing %d files...", $this->phpfinder->count()));
        $compil->run($this->phpfinder->getIterator());

        $packageDir = $input->getArgument('dir');
        chdir($packageDir);
        $cmd = new PhpUnit\Command($classMap->getArrayCopy());
        $cmd->run(array('-c', $packageDir), false);

        $realCall = PhpUnit\Command::$callLink;

        foreach ($this->fineTuning['calling'] as $caller => $cfg) {
            if (array_key_exists($caller, $realCall)) {
                foreach ($cfg['ignore'] as $idx => $called) {
                    if (array_key_exists($called, $realCall[$caller])) {
                        echo "removing $caller -> $called \n";
                        unset($this->fineTuning['calling'][$caller]['ignore'][$idx]);
                    }
                }
                $this->fineTuning['calling'][$caller]['ignore'] = array_values($this->fineTuning['calling'][$caller]['ignore']);
                if (0 == count($this->fineTuning['calling'][$caller]['ignore'])) {
                    unset($this->fineTuning['calling'][$caller]);
                }
            }
        }

        file_put_contents($this->newConfigFile, Yaml::dump(array('graph' => $this->fineTuning), 5));
    }

}