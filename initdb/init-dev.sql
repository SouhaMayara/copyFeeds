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