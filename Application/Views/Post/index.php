<div class="arrange cb">
    <label for="searchTerm">Search Posts:</label>
    <input id="searchTerm" type="text" value="">
    <input id="searchSubmit" class="search" type="submit" name="submit" value="Go">
</div>

<div class="arrange cb">
    <label for="categoryNo">Filter:</label>
    <select class="styled-select" id="categoryNo" name="categoryNo">
        <option value="all">All Categories</option>
        <?php foreach ($body['categories'] as &$category) {
            if (is_array($category)) { ?>
                <option <?php if (isset($body['catFilter']) && $body['catFilter'] == $category['categoryNo'] ) { echo 'selected'; } ?> value="<?php echo $category['categoryNo'] ?>">&nbsp;&nbsp;<?php echo $category['name'] ?></option>
                <?php foreach ($category as &$subcategory) {
                    if (is_array($subcategory)) { ?>
                        <option value="<?php echo $subcategory['categoryNo'] ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $subcategory['name'] ?></option>
                    <?php }
                }?>
            <?php }
        }?>
    </select>

    <select class="styled-select" id="orderBy" name="orderBy">
        <option value="displayName">Author</option>
        <option selected value="dateModified">Date</option>
        <option value="rating">Rating</option>
    </select>

    <select class="styled-select" id="orderDir" name="orderDir">
        <option value="ASC">Ascending</option>
        <option selected value="DESC">Descending</option>
    </select>
</div>

<div class="content-container">
<?php if ($body['posts']['found']) { ?>
    <?php foreach ($body['posts'] as $post) {
        if (is_array($post)) {?>
            <section class="content-child">
                <a href="<?php echo URL_SUB_DIR ?>/post/view_post/<?php echo $post['postNo'] ?>">
                    <div class="cb">
                        <img src="<?php echo URL_SUB_DIR ?>/images/<?php echo $post['imageName'] ?>" alt="' + row.name + ' image">
                        <div class="info cb">
                            <h2><?php echo $post['title'] ?></h2>
                            <p class="content"></p>
                            <footer class="cb">AUTHOR: <?php echo $post['displayName'] ?><span class="rating"></span><span class="fr tar"><?php echo $post['dateModified'] ?><br><?php echo $post['catParent'] ?>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<?php echo $post['catName'] ?></span></footer>
                        </div>
                    </div>
                </a>
            </section>
            <script>
                $(".rating").rateYo({
                    starWidth: "14px",
                    readOnly: true,
                    rating: <?php echo $post['rating'] ?>
                });

                var content = <?php echo json_encode($post['content']); ?>;
                $('.content:last').append(marked(content.length > 265 ?  content.substring(0, 265 - 3) + "..." : content));
            </script>
        <?php } ?>
    <?php } ?>
<?php } else { ?>
    <p>Sorry, no posts were found.</p>
<?php } ?></div>
<ul id="pagination">
    <?php if (isset($body['posts']['pagCount']) && $body['posts']['pagCount'] > 1) { ?>
        <li class="selected">1</li>
        <?php for ($i = 2; $i <= $body['posts']['pagCount']; $i++) { ?>
            <li><?php echo $i ?></li>
        <?php }
    }?>
</ul>

<script>
    // AJAX search, category filtering, pagination, order by and order direction
    $(document).ready(function() {
        function submit(pagNo) {
            $.ajax({
                method: "POST",
                url: '<?php echo URL_SUB_DIR ?>/post/index/' + $('#categoryNo').val(),
                data: {
                    ajax: true,
                    searchTerm: $('#searchTerm').val(),
                    pagNo: pagNo,
                    orderBy: $('#orderBy').val(),
                    orderDir: $('#orderDir').val()
                }
            }).done(function (result) {

                result = JSON.parse(result);

                if (result && result.hasOwnProperty('posts') && result.posts) {
                    render(result);
                } else {
                    $('.flex-container').html('<p>An error occured returning posts.</p>');
                }
            }).fail(function () {
                $('.flex-container').html('<p>An error occured returning posts.</p>');
            });
        }

        $('#categoryNo, #orderBy, #orderDir').change(function() {
            submit(1);
        });

        $('#searchSubmit').click(function() {
            submit(1);
        });

        $('#searchTerm').keypress(function (e) {
            var key = e.which;
            if(key == 13)  // Enter key
            {
                submit(1)
            }
        });

        function handlePag() {
            $("#pagination li").on("click", function(){
                submit($(this).text());
            });
        }

        handlePag();

        function render(result) {
            var posts = $.map(result.posts, function (value) {
                return [value];
            });

            // Using $ to remind ourselves it's a jquery css var
            var $fc = $('.content-container');
            var $pag = $('#pagination');

            $fc.html('');
            $pag.html('');

            posts.forEach(function (row) {
                if (row.title) {
                    $fc.append('<section class="content-child"> \
                                <a href="<?php echo URL_SUB_DIR ?>/post/view_post/' + row.postNo + '"> \
                                    <div class="cb"> \
                                        <img src="<?php echo URL_SUB_DIR ?>/images/' + row.imageName + '" alt="' + row.name + ' image"> \
                                        <div class="info cb"> \
                                            <h2>' + row.title + '</h2> \
                                            <p class="content">' + marked(row.content.length > 265 ? row.content.substring(0, 265 - 3) + "..." : row.content) + '</p> \
                                            <footer class="cb">AUTHOR: ' + row.displayName + '<span class="rating"></span><span class="fr tar">' + row.dateModified + '<br>' + row.catParent + '&nbsp;&nbsp;&raquo;&nbsp;&nbsp;' + row.catName + '</span></footer> \
                                        </div> \
                                    </div> \
                                </a> \
                            </section>');

                    $(".rating:last").rateYo({
                        starWidth: "14px",
                        readOnly: true,
                        rating: row.rating
                    });
                }
            });

            if (result.posts.found && result.posts.pagCount > 1) {
                for (i = 1; i <= result.posts.pagCount; i++) {
                    if (result.pagNo == i) {
                        $pag.append('<li class="selected">' + i + '</li>');
                    } else {
                        $pag.append('<li>' + i + '</li>');
                    }
                }
            } else if (!result.posts.found) {
                $fc.append('<br><p>Sorry, no posts were found.</p>');
            }
            handlePag();
        }
    });
</script>
