<?php

/**
 * @param PDO $pdo
 * @param integer $postId
 * @throws Exception
 */
function getPostRow(PDO $pdo, $postId) {
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

    return $row;
}

/**
 * Writes a comment to a post
 * 
 * @param PDO $pdo
 * @param integer $postId
 * @param array $commentData
 * @return array
 */
function addCommentToPost(PDO $pdo, $postId, array $commentData)
{
    $errors = array();

    // Validation
    if (empty($commentData['name'])) {
        $errors[] = 'A name is required';
    }
    if (empty($commentData['text'])) {
        $errors[] = 'A comment is required';
    }
    if (!$errors) {
        $sql = "
            INSERT INTO comment
                (name, website, text, created_at, post_id)
            VALUES
                (:name, :website, :text, :created_at, :post_id)
        ";
        $stmt = $pdo->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Cannot prepare statement to insert comment');
        }
        $createdTimestamp = date('Y-m-d H:i:s');
        $result = $stmt->execute(
            array_merge(
                $commentData, 
                array('post_id' => $postId, 'created_at' => $createdTimestamp, )
            )
        );

        if ($result === false) {
            // @todo Renders a database-level message to the user, fix this
            $errorInfo = $stmt->errorInfo();
            if ($errorInfo) {
                $errors[] = $errorInfo[2];
            }
        }
    }

    return $errors;

    // $sql = "
    //     INSERT INTO comment
    //         (post_id, name, text, website)
    //     VALUES
    //         (:post_id, :name, :text, :website)
    // ";
    // $stmt = $pdo->prepare($sql);
    // if ($stmt === false)
    // {
    //     throw new Exception('There was a problem preparing this query');
    // }
    // $result = $stmt->execute(
    //     array(
    //         'post_id' => $postId,
    //         'name' => $commentData['name'],
    //         'text' => $commentData['text'],
    //         'website' => $commentData['website'],
    //     )
    // );
    // if ($result === false)
    // {
    //     throw new Exception('There was a problem running this query');
    // }
    
    // return array('success' => true);
}