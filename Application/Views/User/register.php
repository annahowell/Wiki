<form method="post" id="inlined" class="ajax" action="<?php echo URL_SUB_DIR ?>/user/register" enctype="multipart/form-data">
    <fieldset>
        <legend>&nbsp;Register Account&nbsp;</legend>

        <label for="username">User name<span> (min 4 chars)</span></label>
        <input <?php if (HTML5_VAL) { ?>required pattern=".{4,}" <?php } ?> id="username" name="username" type="text">
        <br>
        <label for="password">Password<span> (min 6 chars)</span></label>
        <input <?php if (HTML5_VAL) { ?>required pattern=".{6,}"<?php } ?> id="password" name="password" type="password">
        <br>
        <label for="passwordConfirmation">Confirm password</label>
        <input <?php if (HTML5_VAL) { ?>required pattern=".{6,}"<?php } ?> id="passwordConfirmation" name="passwordConfirmation" type="password">
        <br>
        <label for="displayname">Display name<span> (min 4 chars)</span></label>
        <input <?php if (HTML5_VAL) { ?>required pattern=".{4,}" <?php } ?> id="displayname" name="displayname" type="text">
        <br>
        <label for="email">Email</label>
        <input <?php if (HTML5_VAL) { ?>required type="email" <?php } else { ?>type="text"<?php } ?> id="email" name="email">
        <br>
        <div id="button-container">
            <a href="<?php echo URL_SUB_DIR ?>/" class="button">Cancel</a>

            <input type="submit" name="submit" value="Register">
        </div>

        <span id="error"><?php if (isset($body['error'])) { echo($body['error']); } ?></span>

    </fieldset>
</form>