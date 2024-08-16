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
        // Use in-memory SQLite databases for testing
        $this->prodDb = new PDO('sqlite::memory:');
        $this->devDb = new PDO('sqlite::memory:');

//        $this->database = $this->getMockBuilder(Database::class)
//            ->onlyMethods(['createConnection'])
//            ->getMock();
//        $this->database->method('createConnection')
//            ->will($this->returnValueMap([
//                ['pgsql:host=postgres;dbname=prod_db', 'user', 'password', $this->prodDb],
//                ['pgsql:host=postgres;dbname=dev_db', 'user', 'password', $this->devDb]
//            ]));
        // Instantiate the Database class with PDO instances
//        $this->database = new Database($this->prodDb, $this->devDb);
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

//    protected function tearDown(): void
//    {
//        $this->database->truncateDevDb();
//    }
}
