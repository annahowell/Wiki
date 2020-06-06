<?php
/**
 * Handles custom 503 Exceptions.
 *
 * Date: 1st April 2018
 * @author: Anna Thomas - s4927945
 * Assignment 2 - Wiki
 */

class Custom503Exception extends CustomException
{
    protected $message =  'The service you requested is temporarily unavailable, please try again later';
    protected $code = 503; // Supports 5.4 onwards being able to generate own HTTP header codes should my code be refactored
    protected $status = '503 Service Unavailable';
}
