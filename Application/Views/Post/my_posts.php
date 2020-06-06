<h1>My Posts</h1>

<div class="button-container-right">
    <a href="<?php echo URL_SUB_DIR ?>/post/create_post" class="button">Create New Post</a>
</div>

<?php if ($body['posts']['found']) { ?>

    <table id="list-table">
        <tr>
            <th class="medium">Title</th>
            <th class="medium narrow-hide">Category</th>
            <th class="small number">Avg. Rating</th>
            <th class="medium number narrow-hide">Date Modified</th>
            <th class="small icon-cell">Edit</th>
            <th class="small icon-cell">Delete</th>
        </tr>

        <?php foreach ($body['posts'] as $post) {
            if (is_array($post)) {?>
                <tr>
                    <td class="medium"><a href="<?php echo URL_SUB_DIR ?>/post/view_post/<?php echo $post['postNo']?>"><?php echo $post['title'] ?></a></td>
                    <td class="medium narrow-hide"><?php echo $post['catName']?></td>
                    <td class="small number"><?php if(is_null($post['average'])) { echo 'Unrated'; } else { echo number_format($post['average'], 1, '.', ''); }?></td>
                    <td class="medium number narrow-hide"><?php echo date('D d/m/Y - g:i A', strtotime($post['dateModified']))?></td>
                    <td class="small icon-cell"><a href="<?php echo URL_SUB_DIR ?>/post/edit_post/<?php echo $post['postNo']?>"><svg class="icon icon-pencil"><use xlink:href="#icon-pencil"></use></svg></a></td>
                    <td class="small icon-cell"><a href="<?php echo URL_SUB_DIR ?>/post/delete_post/<?php echo $post['postNo']?>"><svg class="icon icon-bin"><use xlink:href="#icon-bin"></use></svg></a></td>
                </tr>
            <?php }
        } ?>
    </table>

    <div id="pagination">
        <?php if ($body['posts']['pagCount'] > 1) {
            for ($i = 1; $i <= $body['posts']['pagCount']; $i++) { ?>
            <a <?php if ($i == $body['pagNo']) { ?>class="selected"<?php } ?> href="<?php echo URL_SUB_DIR ?>/post/my_posts/<?php echo $i ?>"><?php echo $i ?></a>
        <?php }
        }?>
    </div>

<?php } else { ?>
    <p>No posts found.</p>
<?php } ?>
