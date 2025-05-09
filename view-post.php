<?php

require_once 'lib/common.php';

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
$stmt = $pdo->prepare(
    'SELECT
        title, created_at, body
    FROM
        post
    WHERE
        id = :id'
);
if ($stmt === false)
{
    throw new Exception('There was a problem preparing this query');
}
$result = $stmt->execute(
    array('id' => $postId, )
);
if ($result === false)
{
    throw new Exception('There was a problem running this query');
}
// Get row
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Swap carriage returns for paragraph breaks
$bodyText = htmlEscape($row['body']);
$paraText = str_replace("\n", "<p></p>", $bodyText);
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
        <p>
            <?php // Already escaped ?>
            <?php echo $paraText ?>
        </p>

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
                    <?php echo htmlEscape($comment['text']) ?>
                </div>
            </div>
        <?php endforeach ?>
    </body>
</html>
