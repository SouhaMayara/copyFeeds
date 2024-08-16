<?php

namespace App\Tests;

use App\Database;
use PDO;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $prodDb;
    private $devDb;
    private $database;

    protected function setUp(): void
    {
        $this->prodDb = new PDO('sqlite::memory:');
        $this->devDb = new PDO('sqlite::memory:');
        $this->database = new Database(true);
    }


    public function testCopyFeed()
    {
        $result = $this->database->copyFeed(1, 'instagram', 1);
        $this->assertTrue($result);

        $feed = $this->database->getDevDb()->query('SELECT * FROM feeds WHERE id = 1')->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($feed);
        $this->assertEquals('test_influencer', $feed['name']);

        $source = $this->database->getDevDb()->query("SELECT * FROM instagram_sources WHERE name = 'test_influencer'")->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($source);
        $this->assertEquals(1000, $source['fan_count']);

        $post = $this->database->getDevDb()->query('SELECT * FROM posts WHERE feed_id = 1')->fetch(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($post);
        $this->assertEquals('http://example.com', $post['url']);
    }
}
