<h1>Edit Existing Post</h1>

<form method="post" class="ajax" id="genericform" action="<?php echo URL_SUB_DIR ?>/post/edit_post/<?php echo $body['post']['postNo'] ?>" enctype="multipart/form-data">
    <fieldset>
        <label for="name">Title:</label>
        <input <?php if (HTML5_VAL) { ?>required<?php } ?> id="title" name="title" type="text" value="<?php echo $body['post']['title'] ?>">

        <label for="content">Content:</label>
        <textarea <?php if (HTML5_VAL) { ?>required<?php } ?> id="content" name="content"><?php echo $body['post']['content'] ?></textarea>

        <label>Existing image: </label>
        <img class="post-update-preview" src="<?php echo URL_SUB_DIR ?>/images/<?php echo $body['post']['imageName']?>">

        <label fpr="imageName">Select new image (optional):</label>
        <input id="imageName" name="imageName" type="file" accept=".jpg, .jpeg, .png">

        <label for ="categoryNo">Select post category:</label>
        <select <?php if (HTML5_VAL) { ?>required<?php } ?> id="categoryNo" name="categoryNo">
            <?php foreach ($body['categories'] as &$category) {
                if (is_array($category)) { ?>
                    <option disabled value="<?php echo $category['categoryNo'] ?>"><?php echo $category['name'] ?></option>
                    <?php foreach ($category as &$subcategory) {
                        if (is_array($subcategory)) { ?>
                            <option <?php if ($subcategory['categoryNo'] == $body['post']['categoryNo']) {?>selected<?php } ?> value="<?php echo $subcategory['categoryNo'] ?>">&nbsp;&nbsp;<?php echo $subcategory['name'] ?></option>
                        <?php }
                    }?>
                <?php }
            }?>
        </select>

        <div id="button-container">
            <a href="<?php echo URL_SUB_DIR ?>/post/my_posts" class="button" style="margin-left:0">Cancel</a>
            <input type="submit" name="submit" value="Update Post">
        </div>

        <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>
    </fieldset>
</form>

<script>
new SimpleMDE({
    element: document.getElementById("content"),
    forceSync: true,
    hideIcons:["heading"],
    showIcons: ["strikethrough", "heading-1", "heading-2", "heading-3"],
    promptURLs: true,
    spellChecker: true,
    styleSelect1edText: false,
    tabSize: 4,
    toolbarTips: true,
    preview: true
});
</script>
