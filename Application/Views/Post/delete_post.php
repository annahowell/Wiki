<h1>Delete Your Post</h1>

<?php if ($body['post']['found']) { ?>
    <form method="post" id="genericform" action="<?php echo URL_SUB_DIR ?>/post/delete_post/<?php echo $body['post']['postNo'] ?>">
        <p style="">Are you sure you wish to delete your post titled '<?php echo $body['post']['title'] ?>' ?</p>

        <div id="button-container">
            <a href="<?php echo URL_SUB_DIR ?>/post/my_posts" class="button" style="margin-left:0">Cancel</a>
            <input type="submit" name="submit" value="Delete Post">
        </div>
        <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>
    </form>
<?php } else { ?>
    <p>Post does not exist.</p>
<?php } ?>
