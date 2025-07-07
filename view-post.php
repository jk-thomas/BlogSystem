<?php

require_once 'lib/common.php';
require_once 'lib/view-post.php';

// Get post ID
if (isset($_GET['post_id']))
{
    $postId = (int) $_GET['post_id'];
}
else
{
    $postId = 0;
}
// Connect to the database, run a query, handle errors
$pdo = getPDO();
$row = getPostRow($pdo, $postId);

// If the post does not exist
if (!$row) {
    redirectAndExit('index.php?not_found=1');
}

$errors = null;
if ($_POST)
{
    $commentData = array(
        'name' => $_POST['comment-name'],
        'website' => $_POST['comment-website'],
        'text' => $_POST['comment-text'],
    );
    $errors = addCommentToPost(
        $pdo,
        $postId,
        $commentData
    );
    // If there are no errors, redirect back to self and redisplay
    if (!$errors)
    {
        redirectAndExit('view-post.php?post_id=' . $postId);
    }
}
else {
    $commentData = array(
        'name' => '',
        'website' => '',
        'text' => '',
    );
}

// // Swap carriage returns for paragraph breaks
// $bodyText = htmlEscape($row['body']);
// $paraText = str_replace("\n", "<p></p>", $bodyText);
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            A blog application |
            <?php echo htmlEscape($row['title']) ?>
        </title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    </head>
    <body>
        <?php require 'templates/title.php' ?>

        <h2>
            <?php echo htmlEscape($row['title']) ?>
        </h2>
        <div>
            <?php echo convertSqlDate($row['created_at']) ?>
        </div>
        <?php // Already escaped ?>
        <?php echo convertNewLinesToParagraphs($row['body']) ?>

        <h3><?php echo countCommentsForPost($postId) ?> comments</h3>

        <?php foreach (getCommentsForPost($postId) as $comment) : ?>
            <?php // Horizontal rule-off to split ?>
            <hr />
            <div class="comment">
                <div class="comment-meta">
                    Comment from
                    <?php echo htmlEscape($comment['name']) ?>
                    on
                    <?php echo convertSqlDate($comment['created_at']) ?>
                </div>
                <div class="comment-body">
                    <?php // Escaped ?>
                    <?php echo convertNewLinesToParagraphs($comment['text']) ?>
                </div>
            </div>
        <?php endforeach ?>

        <?php require 'templates/comment-form.php' ?>
    </body>
</html>
