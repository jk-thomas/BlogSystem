<?php 
/**
 * @var $errors string
 * @var $commentData array
 */
?>

<?php // Report errors in bp list ?>
<?php if ($errors): ?>
    <div class="error box comment-margin">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<h3>Add your comment</h3>

<form 
    action="view-post.php?action=add-comment&amp;post_id=<?php echo $postId?>"
    method="post"
    class="comment-form user-form"
>
    <div>
        <label for="comment-name">
            Name:
        </label>
        <input
            type="text"
            id="comment-name"
            name="comment-name"
            value="<?php echo htmlEscape($commentData['name']) ?>"
        />
    </div>
    <div>
        <label for="comment-website">
            Website:
        </label>
        <input
            type="text"
            id="comment-website"
            name="comment-website"
            value="<?php echo htmlEscape($commentData['website']) ?>"
        />
    </div>
    <div>
        <label for="comment-text">
            Comment:
        </label>
        <textarea
            id="comment-text"
            name="comment-text"
            rows="8"
            cols="70"
        ><?php echo htmlEscape($commentData['text']) ?></textarea>
    </div>
    <div>
        <input type="submit" value="Submit comment" />
    </div>
</form>
