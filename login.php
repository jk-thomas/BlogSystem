<?php
require_once 'lib/common.php';

if (version_compare(PHP_VERSION, '5.3.7') < 0) {
    throw new Exception(
        'This systems needs PHP 5.3.7 or later'
    );
}

session_start();

if (isLoggedIn()) {
    redirectAndExit('index.php');
}

// Handle form posting
$username = '';
if ($_POST) {
    // Init database
    $pdo = getPDO();

    // Redirect only if password incorrect
    $username = $_POST['username'];
    $ok = tryLogin($pdo, $username, $_POST['password']);
    if ($ok) {
        login($username);
        redirectAndExit('index.php');
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            A blog application | Login
        </title>
        <?php require 'templates/head.php' ?>
    </head>
    <body>
        <?php require 'templates/title.php' ?>
        <?php // If username, then error ?>
        <?php if ($username): ?>
            <div class="error box">
                The username or password is incorrect, try again
            </div>
        <?php endif ?>

        <p>Login here:</p>

        <form 
            method='post'
            class="user-form"
        >
            <div>
                <label for="username">
                    Username:
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?php echo htmlEscape($username) ?>"
                />
            </div>
            <div>
                <label for="password">
                    Password:
                </label>
                <input 
                    type="password"
                    id="password"
                    name="password" 
                />
            </div>
            <input type="submit" name="submit" value="Login" />
        </form>
    </body>
</html>
