<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'lib/common.php';

function installBlog()
{
    // Get the PDO DSN string
    $root = getRootPath();
    $database = getDatabasePath();
    $error = '';
    // A security measure, to avoid resetting the database if it exists
    if (is_readable($database) && filesize($database) > 0)
    {
        $error = 'Please delete the existing database manually before installing it afresh';
    }
    // Create an empty file for the database
    if (!$error)
    {
        $createdOk = @touch($database);
        if (!$createdOk)
        {
            $error = sprintf(
                'Could not create the database, please allow the server to create new files in \'%s\'',
                dirname($database)
            );
        }
    }
    // $sql = file_get_contents($root . '/data/init.sql');
    // if ($sql === false) {
    //     echo "<div style='background:#faa;padding:10px;'>Cannot read init.sql</div>";
    // } else {
    //     echo "<div style='background:#efe;border:1px solid #aaa;padding:10px;'><strong>Loaded SQL:</strong><pre>$sql</pre></div>";
    // }
    // Grab the SQL commands to run on the database
    if (!$error)
    {
        $sql = file_get_contents($root . '/data/init.sql');
        if ($sql === false)
        {
            $error = 'Cannot find SQL file';
        }
    }
    $sql = file_get_contents($root . '/data/init.sql');
    echo "<pre style='background:#ddd;padding:10px'>SQL LOADED:<br>" . htmlentities($sql) . "</pre>";
    
    // Connect to the new database and try to run the SQL commands
    if (!$error)
    {
        $pdo = getPDO();
        $result = $pdo->exec($sql);
        if ($result === false)
        {
            $error = 'Could not run SQL: ' . print_r($pdo->errorInfo(), true);
        }
    }
    // See how many rows created, if any
    $count = array();
    foreach(array('post', 'comment') as $tableName)
    {
        if (!$error)
        {
            $sql = "SELECT COUNT(*) AS c FROM " . $tableName;
            $stmt = $pdo->query($sql);
            if ($stmt)
            {
                // Store each count in an associative array
                $count[$tableName] = $stmt->fetchColumn();
            }
        }
    }
    return array($count, $error);
}
// Store in session
session_start();
// Only run installer when responding to the form
if ($_POST)
{
    // Install
    list($_SESSION['count'], $_SESSION['error']) = installBlog();
    // Redirect from POST to GET
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['REQUEST_URI'];
    header('Location: http://' . $host . $script);
    exit();
}
// Install checker
$attempted = false;
if ($_SESSION)
{
    $attempted = true;
    $count = $_SESSION['count'];
    $error = $_SESSION['error'];
    // Unset session variables, report the install/failure once
    unset($_SESSION['count']);
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Blog installer</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <style type="text/css">
            .box {
                border: 1px dotted silver;
                border-radius: 5px;
                padding: 4px;
            }
            .error {
                background-color: #ff6666;
            }
            .success {
                background-color: #88ff88;
            }
        </style>
    </head>
    <body>
        <?php if ($attempted): ?>
            <?php if ($error): ?>
                <div class="error box">
                    <?php echo $error ?>
                </div>
            <?php else: ?>
                <div class="success box">
                    The database and demo data was created OK.
                    <?php foreach (array('post', 'comment') as $tableName): ?>
                        <?php if (isset($count[$tableName])): ?>
                            <?php // Prints the count ?>
                            <?php echo $count[$tableName] ?> new
                            <?php // Prints the name of the thing ?>
                            <?php echo $tableName ?>s
                            were created.
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
                <p>
                    <a href="index.php">View the blog</a>,
                    or <a href="install.php">install again</a>.
                </p>
            <?php endif ?>
        <?php else: ?>
            <p>Click the install button to reset the database.</p>
            <form method="post">
                <input
                    name="install"
                    type="submit"
                    value="Install"
                />
            </form>
        <?php endif ?>
    </body>
</html>
