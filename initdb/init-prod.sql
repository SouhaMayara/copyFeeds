-- Create tables
CREATE TABLE feeds
(
    id   SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE instagram_sources
(
    id        SERIAL PRIMARY KEY,
    name      VARCHAR(255) NOT NULL,
    fan_count INTEGER      NOT NULL,
    feed_id   INTEGER REFERENCES feeds (id)
);

CREATE TABLE tiktok_sources
(
    id        SERIAL PRIMARY KEY,
    name      VARCHAR(255) NOT NULL,
    fan_count INTEGER      NOT NULL,
    feed_id   INTEGER REFERENCES feeds (id)
);

CREATE TABLE posts
(
    id      SERIAL PRIMARY KEY,
    url     TEXT NOT NULL,
    feed_id INTEGER REFERENCES feeds (id)
);

-- Insert initial data
INSERT INTO feeds (name)
VALUES ('test_influencer');
INSERT INTO instagram_sources (name, fan_count, feed_id)
VALUES ('test_influencer', 1000, 1);
INSERT INTO tiktok_sources (name, fan_count, feed_id)
VALUES ('test_influencer', 2000, 1);
INSERT INTO posts (url, feed_id)
VALUES ('http://example.com', 1);
