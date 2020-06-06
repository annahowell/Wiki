<?php
require_once APP_DIR . '/Core/Model.php';

/**
 * User model, expands upon the base model's functionality to validate registration, add new users and
 * validate login.
 *
 * Date: 1st February 2018
 * @author: Anna Thomas - s4927945
 * Assignment 2 - Wiki
 */
class UserModel extends Model
{

    /** ------------------------------------------------------------------------------------------------------
     * Provides simple validation for user login
     *
     * @param  $postData    Post data sent by the form
     *
     * @return $result      A success boolean of the validation along with any error message generated
     */
    public function validateLogin($postData)
    {
        $sql = 'SELECT userNo, username, password, displayname FROM user WHERE username = :username';
        $query = $this->db->prepare($sql);

        $query->bindParam(':username', $postData['username'], PDO::PARAM_STR);
        $query->execute();

        $result = $query->fetch(PDO::FETCH_ASSOC);

        $hashedPassword = crypt($postData['password'], '$6$rounds=150000$PerUserCryptoRandomSalt$');

        // Purposefully leaving the failed state ambiguous so as to not inform users if a username is valid
        // or not for security reasons
        if($hashedPassword == $result['password'])
        {
            $result['success'] = TRUE;
        }
        else
        {
            $result['success'] = FALSE;
            $result['error'] = 'Invalid username or password.';
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Adds a new user to the user table of the DB assuming the post data passes validation
     *
     * @param  $postData    Post data sent by the form
     *
     * @return $result      A success boolean of the validation along with any error message generated
     */
    public function register($postData)
    {
        $result = $this->validateRegistration($postData);

        if ($result['success']) {

            $hashedPassword = crypt($postData['password'], '$6$rounds=150000$PerUserCryptoRandomSalt$');

            $sql = 'INSERT INTO user (username, password, displayname, email) VALUES (:username, :password, :displayname, :email)';

            $query = $this->db->prepare($sql);
            $query->bindParam(':username',    $postData['username'],    PDO::PARAM_STR);
            $query->bindParam(':password',    $hashedPassword,      PDO::PARAM_STR);
            $query->bindParam(':displayname', $postData['displayname'], PDO::PARAM_STR);
            $query->bindParam(':email',       $postData['email'],       PDO::PARAM_STR);

            $query->execute();

            $result['userNo'] = $this->db->lastInsertId();
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Provides simple validation for new user registration
     *
     * @param  $postData    Post data sent by the calling function
     *
     * @return $result      A success boolean of the validation along with any error message generated
     */
    public function validateRegistration($postData)
    {
        $result['success']  = FALSE;
        $desiredUsername    = $this->getRowFromTable('user', 'username', $postData['username']);
        $desiredDisplayname = $this->getRowFromTable('user', 'displayname', $postData['displayname']);

        // We're using php 5.3.3 so we'll use trim($foo) == FALSE instead of empty(trim($foo))
        if (trim($postData['username']) == FALSE)
        {
            $result['error'] = 'Please enter a username';
        }
        else if (strlen(trim($postData['username'])) < 4)
        {
            $result['error'] = 'Username must be at least 4 characters';
        }
        else if ($desiredUsername['found'])
        {
            $result['error'] = 'Username not valid'; // Intentionally ambiguous
        }
        else if ($desiredDisplayname['found'])
        {
            $result['error'] = 'Displayname not valid'; // Intentionally ambiguous
        }
        else if (trim($postData['password']) == FALSE)
        {
            $result['error'] = 'Please enter a password';
        }
        else if (strlen(trim($postData['password'])) < 6)
        {
            $result['error'] = 'Password must be at least 6 characters';
        }
        else if (trim($postData['passwordConfirmation']) == FALSE)
        {
            $result['error'] = 'Please enter password confirmation';
        }
        else if (trim($postData['password']) != trim($postData['passwordConfirmation']))
        {
            $result['error'] = 'Passwords do not match';
        }
        else if (trim($postData['displayname']) == FALSE)
        {
            $result['error'] = 'Please enter a display name';
        }
        else if (trim($postData['email']) == FALSE)
        {
            $result['error'] = 'Please enter your email';
        }
        else if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL))
        {
            $result['error'] = 'Please enter a valid email address';
        }
        else if (strtolower($postData['username']) == strtolower($postData['displayname']))
        {
            $result['error'] = 'Display name must be different to username';
        }
        else
        {
            $result['success'] = TRUE;
        }

        return $result;
    }
}
