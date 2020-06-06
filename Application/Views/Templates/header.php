<!DOCTYPE html>
<html lang="en" DIR="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Wiki - Anna Thomas(s4927945)</title>

    <link rel="shortcut icon" href="<?php echo URL_SUB_DIR ?>/images/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="<?php echo URL_SUB_DIR ?>/css/style.css"/>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="<?php echo URL_SUB_DIR ?>/js/marked.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

    <?php if (isset($body['location']) && $body['location'] == 'home' || isset($body['location']) && $body['location'] == 'my_posts' || isset($body['location']) && $body['location'] == 'create_post') { ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <svg aria-hidden="true" style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <defs>
            <symbol id="icon-pencil" viewBox="0 0 32 32">
                <title>pencil</title>
                <path d="M27 0c2.761 0 5 2.239 5 5 0 1.126-0.372 2.164-1 3l-2 2-7-7 2-2c0.836-0.628 1.874-1 3-1zM2 23l-2 9 9-2 18.5-18.5-7-7-18.5 18.5zM22.362 11.362l-14 14-1.724-1.724 14-14 1.724 1.724z"></path>
            </symbol>
            <symbol id="icon-bin" viewBox="0 0 32 32">
                <title>bin</title>
                <path d="M4 10v20c0 1.1 0.9 2 2 2h18c1.1 0 2-0.9 2-2v-20h-22zM10 28h-2v-14h2v14zM14 28h-2v-14h2v14zM18 28h-2v-14h2v14zM22 28h-2v-14h2v14z"></path>
                <path d="M26.5 4h-6.5v-2.5c0-0.825-0.675-1.5-1.5-1.5h-7c-0.825 0-1.5 0.675-1.5 1.5v2.5h-6.5c-0.825 0-1.5 0.675-1.5 1.5v2.5h26v-2.5c0-0.825-0.675-1.5-1.5-1.5zM18 4h-6v-1.975h6v1.975z"></path>
            </symbol>
        </defs>
    </svg>
    <?php } ?>
</head>
<body>

<header>
    <div class="container cb">
        <div id="logo">
            <a href="<?php echo URL_SUB_DIR ?>/">ShareDiscovery</a>
        </div>
        <p class="welcome cb">Welcome <?php if (isset($body['username'])) { echo $body['username']; }?></p>
        <nav>
            <ul>
                <?php if (isset($body['errorStatus'])) { ?>
                <li <?php if (isset($body['location']) && $body['location'] == 'home') { ?>class="active"<?php } ?>><a href="<?php echo URL_SUB_DIR ?>/">Home</a></li>
                <?php } else { ?>
                    <li <?php if (isset($body['location']) && $body['location'] == 'categories') { ?>class="active"<?php } ?>><a href="<?php echo URL_SUB_DIR // Intentional Duplicate?>/post/index/">Categories&nbsp;&nbsp;v</a>
                        <ul>
                            <li><a href="<?php echo URL_SUB_DIR ?>/post/index/">All</a>
                                <?php foreach ($body['categories'] as &$category) {
                                if (is_array($category)) { ?>
                                    <li>
                                        <a href="<?php echo URL_SUB_DIR ?>/post/index/<?php echo $category['categoryNo'] ?>"><?php echo $category['name'] ?></a>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </li>

                    <?php if (isset($body['userLevel']) && $body['userLevel'] == USER_AUTHED) { ?>
                        <li <?php if (isset($body['location']) && $body['location'] == 'create_post') { ?>class="active"<?php } ?>>
                            <a href="<?php echo URL_SUB_DIR ?>/post/create_post">Create Post</a></li>
                        <li <?php if (isset($body['location']) && $body['location'] == 'my_posts') { ?>class="active"<?php } ?>>
                            <a href="<?php echo URL_SUB_DIR ?>/post/my_posts">My Posts</a></li>
                        <li><a href="<?php echo URL_SUB_DIR ?>/user/logout">Logout</a></li>
                    <?php } else { ?>
                        <li <?php if (isset($body['location']) && $body['location'] == 'login') { ?>class="active"<?php } ?>>
                            <a href="<?php echo URL_SUB_DIR ?>/user/login">Login</a></li>
                        <li <?php if (isset($body['location']) && $body['location'] == 'register') { ?>class="active"<?php } ?>>
                            <a href="<?php echo URL_SUB_DIR ?>/user/register">Register</a></li>
                    <?php }
                }?>
            </ul>
        </nav>
    </div>

</header>

<main>
    <div class="container">