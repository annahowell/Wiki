<?php
/**
 * Handles custom 404 Exceptions.
 *
 * Date: 1st April 2018
 * @author: Anna Thomas - s4927945
 * Assignment 2 - Wiki
 */

class Custom404Exception extends CustomException
{
    protected $message =  'The page you requested was not found';
    protected $code = 404;
    protected $status = '404 Not Found';
}
