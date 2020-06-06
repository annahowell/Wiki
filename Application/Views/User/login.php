<form method="post" id="inlined" class="ajax" action="<?php echo URL_SUB_DIR ?>/user/login">
<fieldset>
    <legend>&nbsp;User Login&nbsp;</legend>

    <label for="username">Username:</label>
    <input <?php if (HTML5_VAL) { ?>required<?php } ?> id="username" name="username" type="text" placeholder="Username">
    <br>
    <label for="password">Password:</label>
    <input <?php if (HTML5_VAL) { ?>required<?php } ?> id="password" name="password" type="password" placeholder="Password">

    <div id="button-container">
        <a href="<?php echo URL_SUB_DIR ?>/user/register" class="button">Register</a>
        <input type="submit" name="submit" value="Login">
    </div>

    <span id="error"><?php if (isset($body['message'])) { echo($body['message']); } ?></span>
</fieldset>
</form>