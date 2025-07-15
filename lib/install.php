<?php

/**
 * Blog installer function
 * 
 * @return array(count array, error string)
 */
function installBlog(PDO $pdo) {
    // Get project paths
    $root = getRootPath();
    $database = getDatabasePath();
    $error = '';
    // A security measure, to avoid resetting the database if it exists
    if (is_readable($database) && filesize($database) > 0) {
        $error = 'Please delete the existing database manually before installing it afresh';
    }
    // Create an empty file for the database
    if (!$error) {
        $createdOk = @touch($database);
        if (!$createdOk) {
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
    if (!$error) {
        $sql = file_get_contents($root . '/data/init.sql');
        if ($sql === false)
        {
            $error = 'Cannot find SQL file';
        }
    }
    $sql = file_get_contents($root . '/data/init.sql');
    echo "<pre style='background:#ddd;padding:10px'>SQL LOADED:<br>" . htmlentities($sql) . "</pre>";

    // Connect to the new database and try to run the SQL commands
    if (!$error) {
        // $pdo = getPDO();
        $result = $pdo->exec($sql);
        if ($result === false)
        {
            $error = 'Could not run SQL: ' . print_r($pdo->errorInfo(), true);
        }
    }
    // See how many rows created, if any
    $count = array();
    foreach(array('post', 'comment') as $tableName) {
        if (!$error) {
            $sql = "SELECT COUNT(*) AS c FROM " . $tableName;
            $stmt = $pdo->query($sql);
            if ($stmt) {
                // Store each count in an associative array
                $count[$tableName] = $stmt->fetchColumn();
            }
        }
    }
    return array($count, $error);
}

/**
 * Creates a new user in the database
 * 
 * @param PDO $pdo
 * @param string $username
 * @param integer $length
 * @return array Tuple of (password, error)
 */
function createUser(PDO $pdo, $username, $length = 10) {
    // Algo creates rand password
    $alphabet = range(ord('A'), ord('z'));
    $alphabetLength = count($alphabet);

    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $letterCode = $alphabet[rand(0, $alphabetLength - 1)];
        $password .= chr($letterCode);
    }

    $error = '';

    // Insert credentials into database
    $sql = "
        INSERT INTO
            user
            (username, password, created_at)
            VALUES (
                :username, :password, :created_at
            )
    ";

    $stmt = $pdo->prepare($sql);
    if ($stmt === false) {
        $error = 'Could not prepare the user creation';
    }

    if (!$error) {
        // Create a hash of password
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($hash === false) {
            $error = 'Password hashing failed';
        }
    }

    // Insert user details and hashed password
    if (!$error) {
        $result = $stmt->execute(
            array(
                'username' => $username,
                'password' => $hash,
                'created_at' => getSqlDateForNow(),
            )
        );
        if ($result === false) {
            $error = 'Could not run the user creation';
        }
    }

    if ($error) {
        $password = '';
    }

    return array($password, $error);
}
