-- TESTING 12345: init.sql was loaded
/**
 * Database creation script
 */

 /* Foreign key constraints enabled */
 PRAGMA foreign_keys = ON;

 DROP TABLE IF EXISTS user;
 CREATE TABLE user (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    username VARCHAR NOT NULL,
    password VARCHAR NOT NULL,
    created_at VARCHAR NOT NULL,
    is_enabled BOOLEAN NOT NULL DEFAULT true
);

INSERT INTO
    user
    (
        username, password, created_at, is_enabled
    )
    VALUES
    (
        "admin", "unhashed-pass", datetime('now', '-3 months'), 0
    )
;

DROP TABLE IF EXISTS post;
CREATE TABLE post (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    title VARCHAR NOT NULL,
    body VARCHAR NOT NULL,
    user_id INTEGER NOT NULL,
    created_at TEXT NOT NULL,
    updated_at VARCHAR,
    FOREIGN KEY (user_id) REFERENCES user(id)
);
INSERT INTO
    post
    (
        title, body, user_id, created_at
    )
    VALUES(
        'Here''s our first post',
        'This is the body of the first post.
It is split into paragraphs.',
        1,
        -- date('now', '-2 months')
        -- '2025-06-01 12:34:56'
        datetime('now', '-2 months', '-45 minutes', '+10 seconds')
    )
;
INSERT INTO
    post
    (
        title, body, user_id, created_at
    )
    VALUES(
        "Now for a second article",
        "This is the body of the second post.
This is another paragraph.",
        1,
        datetime('now', '-40 days', '+81 minutes', '+37 seconds')
    )
;
INSERT INTO
    post
    (
        title, body, user_id, created_at
    )
    VALUES(
        "Here's a third post",
        "This is the body of the third post.
This is split into paragraphs.",
        1,
        datetime('now', '-13 days', '+198 minutes', '+51 seconds')
    )
;

DROP TABLE IF EXISTS comment;

CREATE TABLE comment (
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    post_id INTEGER NOT NULL,
    created_at VARCHAR NOT NULL,
    "name" VARCHAR NOT NULL,
    website VARCHAR,
    "text" VARCHAR NOT NULL,
    FOREIGN KEY (post_id) REFERENCES post(id)
);

INSERT INTO
    comment
    (
        post_id, created_at, "name", website, "text"
    )
    VALUES(
        1,
        datetime('now', '-10 days', '+231 minutes', '+7 seconds'),
        'Jimmy',
        'http://example.com',
        "This is Jimmy's contribution"
    )
;

INSERT INTO
    comment
    (
        post_id, created_at, "name", website, "text"
    )
    VALUES(
        1,
        datetime('now', '-8 days', '+549 minutes', '+32 seconds'),
        'Jonny',
        'http://anotherexample.com',
        "This is a comment from Jonny"
    )
;
