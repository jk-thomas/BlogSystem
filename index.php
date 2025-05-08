<?php
// Work out the path to the database, so SQLite/PDO can connect
// $root = __DIR__;
// $database = $root . '/data/data.sqlite';
// $dsn = 'sqlite:' . $database;

require_once 'lib/common.php';

// Connect to the database, run a query, handle errors
// $pdo = new PDO($dsn);
$pdo = getPDO();

$stmt = $pdo->query(
    'SELECT
        id, title, created_at, body
    FROM
        post
    ORDER BY
        created_at DESC'
);
if ($stmt === false)
{
    throw new Exception('There was a problem running this query');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>A blog application</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    </head>
    <body>
        <!-- <h1>Blog title</h1>
        <p>This paragraph summarises what the blog is about.</p> -->
        <?php require 'templates/title.php' ?>

        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <h2>
                <?php echo htmlEscape($row['title']) ?>
            </h2>
            <div>
                <?php echo $row['created_at'] ?>
            </div>
            <p>
                <?php echo htmlEscape($row['body']) ?>
            </p>
            <p>
                <a 
                    href="view-post.php?post_id=<?php echo $row['id'] ?>"
                >Read more...</a>
            </p>
        <?php endwhile ?>
    </body>
</html>

<!--
<!DOCTYPE html>
<html>
    <head>
        <title>A blog application</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    </head>
    <body>
        <h1>Blog title</h1>
        <p>This paragraph summarises what the blog is about.</p>
       
	    <?php for ($postId = 1; $postId <= 3; $postId++): ?>
            <h2>Article <?php echo $postId ?> title</h2>
            <div>dd Mon YYYY</div>
            <p>A paragraph summarising article <?php echo $postId ?>.</p>
            <p>
                <a href="#">Read more...</a>
            </p>
        <?php endfor ?>

	<!-
	<h2>Article 1 title</h2>
        <div>dd Mon YYYY</div>
        <p>A paragraph summarising article 1.</p>
        <p>
            <a href="#">Read more...</a>
        </p>
        <h2>Article 2 title</h2>
        <div>dd Mon YYYY</div>
        <p>A paragraph summarising article 2.</p>
        <p>
            <a href="#">Read more...</a>
        </p>
	v1->
    </body>
</html>
v2-->
