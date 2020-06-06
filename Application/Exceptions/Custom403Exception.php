<?php
/**
 * Handles custom 403 Exceptions.
 *
 * Date: 1st April 2018
 * @author: Anna Thomas - s4927945
 * Assignment 2 - Wiki
 */

class Custom403Exception extends CustomException
{
    protected $message =  'You do not have permission to access this page';
    protected $code = 403;
    protected $status = '403 Forbidden';
}
