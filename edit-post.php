<?php
require_once 'lib/common.php';
require_once 'lib/edit-post.php';

session_start();

// Don't let non-auth users see this page
if (!isLoggedIn()) {
    redirectAndExit('index.php');
}

// Handle post operation
$errors = array();
if ($_POST) {
    // Validate
    $title = $_POST['post-title'];
    if (!$title) {
        $errors[] = 'The post must have a title';
    }
    $body = $_POST['post-body'];
    if (!$body) {
        $errors[] = 'The post must have a body';
    }

    if (!$errors) {
        $pdo = getPDO();
        $userId = getAuthUserId($pdo);
        $postId = addPost(
            getPDO(),
            $title,
            $body,
            $userId
        );

        if ($postId === false) {
            $errors[] = 'Post operation failed';
        }
    }

    if ($postId === false) {
        redirectAndExit('edit-post.php?post_id=' . $postId);
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>A blog application | New post</title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <?php if ($errors): ?>
            <div class="error box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <form method="post" class="post-form user-form">
            <div>
                <label for="post-title">Title:</label>
                <input
                    id="post-title"
                    name="post-title"
                    type="text"
                />
            </div>
            <div>
                <label for="post-body">Body:</label>
                <textarea
                    id="post-body"
                    name="post-body"
                    rows="12"
                    cols="70"
                ></textarea>
            </div>
            <div>
                <input
                    type="submit"
                    value="Save post"
                />
            </div>
        </form>
    </body>
</html>
