<?php
/**
 * Tries to delete the specified post
 * Delete attached comments, then delete post
 * 
 * @param PDO $pdo
 * @param integer $postId
 * @return boolean Returns true on succesful deletion
 * @throws Exception
 */
function deletePost(PDO $pdo, $postId) {
    $sqls = array(
        // Delete comments first for foreign key
        "DELETE FROM
            comment
        WHERE
            post_id = :id",
        "DELETE FROM
            post
        WHERE
            id = :id",
    );
    foreach ($sqls as $sql) {
        $stmt = $pdo->prepare($sql);
        if ($stmt === false) {
            throw new Exception('There was a problem preparing this query');
        }

        $result = $stmt->execute(
            array('id' => $postId, )
        );

        if ($result === false) {
            break;
        }
    }

    return $result !== false;
}
