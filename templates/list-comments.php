<?php
/**
 * @var $pdo PDO
 * @var $postId integer
 */
?>
<div class="comment-list">
    <h3><?php echo countCommentsForPost($pdo, $postId) ?> comments</h3>

    <?php foreach (getCommentsForPost($pdo, $postId) as $comment): ?>
        <div class="comment">
            <div class="comment-meta">
                Comment form
                <?php echo htmlEscape($comment['name']) ?>
                on
                <?php echo convertSqlDate($comment['created_at']) ?>
            </div>
            <div class="comment-body">
                <?php // Escaped ?>
                <?php echo convertNewLinesToParagraphs($comment['text']) ?>
            </div>
        </div>
    <?php endforeach ?>
</div>
