<?php

namespace App;

use PDO;
use PDOException;

class Database
{
    private $prodDb;
    private $devDb;

    public function __construct($test = null)
    {
        $this->prodDb = $this->createConnection('prod_db', 5432, 'prod_db', 'user', 'password');
        $this->devDb = $this->createConnection('dev_db', 5432, 'dev_db', 'user', 'password');
        if($test){
            $this->truncateDevDb();
        }
    }

    protected function createConnection($host, $port, $dbname, $username, $password)
    {
		$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        try {
            return new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function copyFeed($feedId, $only = null, $includePosts = null)
    {
        $feed = $this->fetchFeed($feedId);
        if ($feed) {
            $this->insertFeed($feed);

            if ($only) {
                $this->copySource($feed['name'], $only.'_sources');
            }

            if ($includePosts) {
                $this->copyPosts($feedId, $includePosts);
            }

            return true;
        }

        return false;
    }

    private function fetchFeed($feedId)
    {
        $stmt = $this->prodDb->prepare('SELECT * FROM feeds WHERE id = :feed_id');
        $stmt->execute(['feed_id' => $feedId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function insertFeed($feed)
    {
        $stmt = $this->devDb->prepare('INSERT INTO feeds (id, name) VALUES (:id, :name)');
        $stmt->execute($feed);
    }

    private function copySource($name, $table)
    {
        $stmt = $this->prodDb->prepare("SELECT * FROM $table WHERE name = :name");
        $stmt->execute(['name' => $name]);
        $source = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($source) {
            $columns = implode(', ', array_keys($source));
            $placeholders = ':' . implode(', :', array_keys($source));

            $insertStmt = $this->devDb->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
            $insertStmt->execute($source);
        }
    }

    private function copyPosts($feedId, $limit)
    {
        $stmt = $this->prodDb->prepare('SELECT * FROM posts WHERE feed_id = :feed_id LIMIT :limit');
        $stmt->bindValue(':feed_id', $feedId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $insertStmt = $this->devDb->prepare('INSERT INTO posts (id, url, feed_id) VALUES (:id, :url, :feed_id)');
        foreach ($posts as $post) {
            $insertStmt->execute($post);
        }
    }

    public function truncateDevDb(): void
    {
        $this->devDb->exec("TRUNCATE feeds CASCADE");
        $this->devDb->exec("TRUNCATE instagram_sources CASCADE");
        $this->devDb->exec("TRUNCATE tiktok_sources CASCADE");
        $this->devDb->exec("TRUNCATE posts CASCADE");
    }

    public function getDevDb(): PDO
    {
        return $this->devDb;
    }
}
