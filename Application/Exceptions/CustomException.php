<?php
/**
 * Custom exception class which inherits PHP's Exception and adds the getStatus method and the status value
 * which is used to properly construct the HTTP Header. Also adds custom var to differenriate between a
 * standard exception and a custom the MVC handles
 *
 * Date: 1st April 2018
 * @author: Anna Thomas - s4927945
 * Assignment 2 - Wiki
 */


class CustomException extends Exception
{
    protected $custom = TRUE;
    protected $status = '';

    /** ------------------------------------------------------------------------------------------------------
     * Getter function for the status value
     *
     * @return       The status string var for this custom exception
     */
    public function getStatus()
    {
        return $this->status;
    }
}
