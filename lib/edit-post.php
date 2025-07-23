<?php

function addPost(PDO $pdo, $title, $body, $userId) {
    // Prepare insert query
    $sql = "
        INSERT INTO
            post
            (title, body, user_id, created_at)
            VALUES
            (:title, :body, :user_id, :created_at)
    ";
    $stmt = $pdo->prepare($sql);
    if ($stmt === false) {
        throw new Exception('Could not prepare post insert query');
    }
    $result = $stmt->execute(
        array(
            'title' => $title,
            'body' => $body,
            'user_id' => $userId,
            'created_at' => getSqlDateForNow()
        )
    );
    if ($result === false) {
        throw new Exception('Could not run post insert query');
    }

    return $pdo->lastInsertId();
}