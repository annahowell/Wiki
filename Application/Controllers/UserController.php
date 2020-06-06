<?php
require_once APP_DIR . '/Core/Controller.php';

/**
 * This is the user controller which handles registration of new users and the logging in and out of registered
 * users
 *
 * Date: 8th Feb 2018
 * @author: Anna Thomas - s4927945
 * Assignment 2 - Wiki
 */
class UserController extends Controller
{
    // We adopt a default permission of false to each controller as best security practice, therefore we need
    // to set our permission variable accordingly, in this case permission is unconditional as anyone can
    // access this.
    protected $methodPermissions = array(
        USER_GUEST  => array('index', 'register', 'login'),
        USER_AUTHED => array('index', 'logout')
    );

    function __construct($model)
    {
        parent::__construct($model);
    }



    /** ------------------------------------------------------------------------------------------------------
     * Simply forwards anyone landing on the index page to login page
     */
    public function index()
    {
        header('Location: ' . URL_SUB_DIR . '/user/login');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for user registration using AJAX, validation is handed off to the model
     */
    public function register()
    {
        $this->body['location'] = 'register';

        // We check for username instead of submit because AJAX won't pass submit
        if(isset($_POST['username']))
        {
            $validation = $this->model->register($_POST);

            if($validation['success'])
            {
                $_SESSION['userNo'] = $validation['userNo'];
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['userLevel'] = USER_AUTHED;

                // If AJAX isn't being used
                if(isset($_POST['submit']))
                {
                    header('Location: ' . URL_SUB_DIR . '/');
                }
                else
                {
                    $result = array('redirect' => URL_SUB_DIR . '/');

                    echo json_encode($result);
                }
            }
            else
            {
                // If AJAX isn't being used
                if(isset($_POST['submit']))
                {
                    $this->body['error'] = $validation['error'];

                    $this->render('User', 'register');
                }
                else
                {
                    $result = array('error' => $validation['error']);

                    echo json_encode($result);
                }
            }
        }
        else
        {
            $this->render('User', 'register');
        }
    }


    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for user login and associated SESSION details, validation is handed off to
     * the model
     */
    public function login()
    {
        $this->body['location'] = 'login';

        // We check for username instead of submit because AJAX won't pass submit
        if(isset($_POST['username']))
        {
            $validation = $this->model->validateLogin($_POST);

            if($validation['success'])
            {
                $_SESSION['userNo'] = $validation['userNo'];
                $_SESSION['username'] = $validation['username'];
                $_SESSION['userLevel'] = USER_AUTHED;

                // If AJAX isn't being used
                if(isset($_POST['submit']))
                {
                    header('Location: ' . URL_SUB_DIR . '/');
                }
                else
                {
                    $result = array('redirect' => URL_SUB_DIR . '/');

                    echo json_encode($result);
                }
            }
            else
            {
                // If AJAX isn't being used
                if(isset($_POST['submit']))
                {
                    $this->body['message'] = $validation['error'];

                    $this->render('User', 'login');
                }
                else
                {
                    $result = array('error' => $validation['error']);

                    echo json_encode($result);
                }
            }
        }
        else
        {
            $this->render('User', 'login');
        }
    }



    /** ------------------------------------------------------------------------------------------------------
     * Destroys the session upon logout and then redirects the user to the homepage
     */
    public function logout()
    {
        session_destroy();
        header('Location: ' . URL_SUB_DIR . '/');
    }
}
