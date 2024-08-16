<?php

namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CopyFeedCommand extends Command
{
    protected static $defaultName = 'copy';
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Copy feed entries from production to development database')
            ->addArgument('feed_id', InputArgument::REQUIRED, 'ID of the feed to copy')
            ->addOption('only', null, InputOption::VALUE_OPTIONAL, 'Specify the source table to copy')
            ->addOption('include-posts', null, InputOption::VALUE_OPTIONAL, 'Number of posts to copy');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $feedId = $input->getArgument('feed_id');
        $only = $input->getOption('only');
        $includePosts = $input->getOption('include-posts');

        if ($this->database->copyFeed($feedId, $only, $includePosts)) {
            $output->writeln('Feed copied successfully.');
        } else {
            $output->writeln('Feed not found.');
        }

        return Command::SUCCESS;
    }
}
