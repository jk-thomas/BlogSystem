<?php

function addPost(PDO $pdo, $title, $body, $userId) {
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

function editPost(PDO $pdo, $title, $body, $postId) {
    // Prepare the insert query
    $sql = "
        UPDATE
            post
        SET
            title = :title,
            body = :body
        WHERE
            id = :post_id
        ";
    $stmt = $pdo->prepare($sql);
    if ($stmt === false) {
        throw new Exception('Could not prepare post update query');
    }

    $result = $stmt->execute(
        array(
            'title' => $title,
            'body' => $body,
            'post_id' => $postId,
        )
    );
    if ($result === false) {
        throw new Exception('Could not run post update query');
    }

    return true;
}
