<?php

/**
 * Handle comment form, redirects upon success
 * 
 * @param PDO $pdo
 * @param integer $postId
 * @param array $commentData
 */
function handleAddComment(PDO $pdo, $postId, array $commentData) {
    $errors = addCommentToPost(
        $pdo,
        $postId,
        $commentData
    );

    if (!$errors) {
        redirectAndExit('view-post.php?post_id=' . $postId);
    }

    return $errors;
}

/**
 * Called to handle the delete comment form, redirects afterwards
 * 
 * $deleteResponse array is expected to be in the form:
 * 
 *      Array ( [6] => Delete )
 * 
 * which comes directly from input elements of this form:
 * 
 *      name="delete-comment[6]"
 * 
 * @param PDO $pdo
 * @param integer $postId
 * @param array $deleteResponse
 */
function handleDeleteComment(PDO $pdo, $postId, array $deleteResponse) {
    if (isLoggedIn()) {
        $keys = array_keys($deleteResponse);
        $deleteCommentId = $keys[0];
        if ($deleteCommentId) {
            deleteComment($pdo, $postId, $deleteCommentId);
        }

        redirectAndExit('view-post.php?post_id=' . $postId);
    }
}

/**
 * Delete specified comment on specified post
 * 
 * @param PDO $pdo
 * @param integer $postId
 * @param integer $commentId
 * @return boolean True if the command executed without errors
 * @throws Exception
 */
function deleteComment(PDO $pdo, $postId, $commentId) {
    // post_id + comment_id for safety
    $sql = "
        DELETE FROM
            comment
        WHERE
            post_id = :post_id
            AND id = :comment_id
    ";
    $stmt = $pdo->prepare($sql);
    if ($stmt === false) {
        throw new Exception('There was a problem preparing this query');
    }

    $result = $stmt->execute(
        array(
            'post_id' => $postId,
            'comment_id' => $commentId,
        )
    );

    return $result !== false;
}

/**
 * Retrieves single post
 * 
 * @param PDO $pdo
 * @param integer $postId
 * @throws Exception
 */
function getPostRow(PDO $pdo, $postId) {
    $stmt = $pdo->prepare(
        'SELECT
            title, created_at, body,
            (SELECT COUNT(*) FROM comment WHERE comment.post_id = post.id) comment_count
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
        // $createdTimestamp = date('Y-m-d H:i:s');
        $result = $stmt->execute(
            array_merge(
                $commentData, 
                array('post_id' => $postId, 'created_at' => getSqlDateForNow(), )
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
