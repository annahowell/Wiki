<?php
/**
 * This is the autoloader, which currently only works for exceptions, future revisions of this
 * MVC will use global autoloading
 *
 * Date: 1st April 2018
 * @author: Anna Thomas - s4927945
 * Assignment 2 - Wiki
 */
spl_autoload_register(function ($class_name)
{
    if (file_exists(APP_DIR . DS . 'Exceptions' . DS . $class_name . '.php'))
    {
        include APP_DIR . DS . 'Exceptions' . DS . $class_name . '.php';
    }
    else
    {
        throw new Exception('Could not find class ' . $class_name);
    }
});

?>
