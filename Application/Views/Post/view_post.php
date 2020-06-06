<?php if ($body['post']['found']) { ?>

<section class="post cb">
    <img src="<?php echo URL_SUB_DIR ?>/images/<?php echo $body['post']['imageName']?>" alt="post image">
    <div class="info">
        <p class="author">By <?php echo $body['post']['displayName']?> on <?php echo date('jS M Y \a\t g:i A', strtotime($body['post']['dateModified'])) ?>
        <br>
        Posted in <?php echo $body['post']['catParent']?>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<?php echo $body['post']['catName']?>
        <div id="postrating">
            <div id="rateYo"></div>
        </div>
        <div class="userrating">
        <?php if (isset($body['userLevel']) && $body['userLevel'] != USER_GUEST) { ?>

            <?php if (isset($body['post']['userRating']) && !is_null($body['post']['userRating'])) { ?>
            <p id="thisrating">You previously rated this <?php echo $body['post']['userRating'] ?> stars</p>
            <?php } else { ?>
            <p id="thisrating">You haven't rated this post</p>
            <?php } ?>
            <select id="rating" name="rating">
                <option value="0" disabled selected><?php if (isset($body['post']['userRating']) && !is_null($body['post']['userRating'])) { ?>Change your rating?<?php } else { ?>Rate post now?<?php } ?></option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>
        <?php } else { ?>
            <p>Please <a href="<?php echo URL_SUB_DIR ?>/user/login">login</a> to rate posts.</p>
        <?php } ?>
        </div>

        <h2><?php echo $body['post']['title'] ?></h2>
        <div id="markdowncontent"></div>

        <?php if (isset($body['post']['userNo']) && $body['post']['userNo'] == $body['userNo'] ) { ?>
            <div class="button-container-right">
                <a href="<?php echo URL_SUB_DIR ?>/post/edit_post/<?php echo $body['post']['postNo'] ?>" class="button">Edit This Post</a>
            </div>
        <?php } ?>
    </div>
</section>

<?php } else { ?>
    <p>Post not found.</p>
<?php } ?>

<script>
    $('#markdowncontent').append(marked(<?php echo json_encode($body['post']['content']); ?>));
</script>

<script>
    // AJAX rating
    $(document).ready(function() {

        $(function () {
            $("#rateYo").rateYo({
                starWidth: "28px",
                readOnly: true,
                rating: <?php if (is_null($body['post']['average'])) { echo 0; } else { echo $body['post']['average']; } ?>
            });

        });

        function rate() {
            $.ajax({
                method: "POST",
                url: '<?php echo URL_SUB_DIR ?>/post/rate_post',
                data: {
                    ajax: true,
                    postNo: <?php echo $body['post']['postNo']?>,
                    rating: $('#rating').val()
                }
            }).done(function (result) {
                result = JSON.parse(result);

                // If result is a valid one
                if (result) {
                    // And if that result contains an error message
                    if (result['error']) {
                        $('#error').html(result['error']);
                    } else {
                        $('#rateYo').rateYo("rating", result.toString());
                        $('#thisrating').text('Success! You just rated this ' + $('#rating').val() + ' stars');
                        $('#rating option:contains("Rate post now?")').text('Change your rating?');
                        $('#rating').val(0);
                    }
                }
                else {
                    $('#error').html('An unspecified error occurred rating this post');
                }
            }).fail(function () {
                $('#error').html('An unspecified error occurred rating this post');
            });
        }

        $('#rating').change(function() {
            rate();
        });
    });
</script>