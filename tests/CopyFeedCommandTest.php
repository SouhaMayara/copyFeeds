<?php

namespace App\Tests;

use App\CopyFeedCommand;
use App\Database;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CopyFeedCommandTest extends TestCase
{
    private $database;
    private $commandTester;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
        $command = new CopyFeedCommand($this->database);

        $application = new Application();
        $application->add($command);

        $this->commandTester = new CommandTester($application->find('copy'));
    }

    public function testExecuteWithValidFeed()
    {
        $this->database->method('copyFeed')->willReturn(true);

        $this->commandTester->execute([
            'feed_id' => 1,
            '--only' => 'instagram',
            '--include-posts' => 5,
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Feed copied successfully.', $output);
    }

    public function testExecuteWithInvalidFeed()
    {
        $this->database->method('copyFeed')->willReturn(false);

        $this->commandTester->execute([
            'feed_id' => 999,
        ]);

        $output = $this->commandTester->getDisplay();
        $this->assertStringContainsString('Feed not found.', $output);
    }
}
