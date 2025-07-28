<?php

require_once 'lib/common.php';

session_start();

// Connect to the database
$pdo = getPDO();

// Run query
$stmt = $pdo->query(
    'SELECT
        id, title, created_at, body
    FROM
        post
    ORDER BY
        created_at DESC'
);
// Handle error
if ($stmt === false) {
    throw new Exception('There was a problem running this query');
}

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
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="post-synopsis">
                    <h2>
                        <?php echo htmlEscape($row['title']) ?>
                    </h2>
                    <div class="meta">
                        <?php echo convertSqlDate($row['created_at']) ?>
                        (<?php echo countCommentsForPost($pdo, $row['id']) ?> comment(s))
                    </div>
                    <p>
                        <?php echo htmlEscape($row['body']) ?>
                    </p>
                    <div class="post-controls">
                        <a 
                            href="view-post.php?post_id=<?php echo $row['id'] ?>"
                        >Read more...</a>
                        <?php if (isLoggedIn()): ?>
                            |
                            <a
                                href="edit-post.php?post_id=<?php echo $row['id'] ?>"
                            >Edit</a>
                        <?php endif ?>
                    </div>
                </div>
            <?php endwhile ?>
        </div>

    </body>
</html>
