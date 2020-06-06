<h1><?php echo $body['errorStatus'] ?></h1>

<?php if (DEV) { ?>
    <pre style="white-space:pre-wrap;"><?php var_dump($body['errorObject']) ?></pre>
<?php } else { ?>
    <p><?php echo $body['errorMessage'] ?></p>
<?php } ?>
