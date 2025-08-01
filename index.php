<?php

require_once 'lib/common.php';

session_start();

// Connect to the database
$pdo = getPDO();
$posts = getAllPosts($pdo);

$notFound = isset($_GET['not_found']);

?>

<!DOCTYPE html>
<html>
    <head>
        <title>A blog application</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <?php if ($notFound): ?>
            <div class="error box">
                Error: cannot find the request blog post
            </div>
        <?php endif ?>
        
        <div class="post-list">
            <?php foreach ($posts as $post): ?>
                <div class="post-synopsis">
                    <h2>
                        <?php echo htmlEscape($post['title']) ?>
                    </h2>
                    <div class="meta">
                        <?php echo convertSqlDate($post['created_at']) ?>
                        (<?php echo $post['comment_count'] ?> comment(s))
                    </div>
                    <p>
                        <?php echo htmlEscape($post['body']) ?>
                    </p>
                    <div class="post-controls">
                        <a 
                            href="view-post.php?post_id=<?php echo $post['id'] ?>"
                        >Read more...</a>
                        <?php if (isLoggedIn()): ?>
                            |
                            <a
                                href="edit-post.php?post_id=<?php echo $post['id'] ?>"
                            >Edit</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

    </body>
</html>
