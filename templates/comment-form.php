<?php ?>
<hr />

<?php // Report errors in bp list ?>
<?php if ($errors): ?>
    <div style="border: 1px solid #ff6666; padding: 6px;">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo $error ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<h3>Add your comment</h3>

<form method="post">
    <p>
        <label for="comment-name">
            Name:
        </label>
        <input
            type="text"
            id="comment-name"
            name="comment-name"
        />
    </p>
    <p>
        <label for="comment-website">
            Website:
        </label>
        <input
            type="text"
            id="comment-website"
            name="comment-website"
        />
    </p>
    <p>
        <label for="comment-text">
            Comment:
        </label>
        <textarea
            id="comment-text"
            name="comment-text"
            rows="8"
            cols="70"
        ></textarea>
    </p>

    <input type="submit" value="Submit comment" />
</form>