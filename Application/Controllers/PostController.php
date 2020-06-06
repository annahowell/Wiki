<?php
require_once APP_DIR . '/Core/Controller.php';

/**
 * This is the Post controller which handles viewing, searching and ordering posts, as well as adding, editing,
 * deleting and rating them
 *
 * Date: 15th Feb 2018
 * @author: Anna Thomas - s4927945
 * Assignment 2 - Wiki
 */
class PostController extends Controller
{
    // We adopt a default permission of false to each controller as best security practice, therefore we need
    // to set our permission variable accordingly, in this case permission is unconditional as anyone can
    // access this.
    protected $methodPermissions = array(
        USER_GUEST  => array('index', 'view_post'),
        USER_AUTHED => array('index', 'view_post', 'rate_post', 'create_post', 'my_posts', 'edit_post', 'delete_post')
    );

    function __construct($model)
    {
        parent::__construct($model);

    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles logic for viewing, searching and ordering posts using AJAX
     *
     * @param $params       Int of the categoryNo to display.
     */
    public function index($params)
    {
        $this->body['location'] = 'categories';

        // Setup our params from the url array with some sane defaults and basic sanitation if values are
        // absent via unary operator
        $categoryNo = isset($params[0])           ? $params[0] : 'all';
        $searchTerm = isset($_POST['searchTerm']) ? strtolower($_POST['searchTerm']) : '%';
        $pagNo      = isset($_POST['pagNo'])      ? $_POST['pagNo'] : '1';
        $orderBy    = isset($_POST['orderBy'])    ? $_POST['orderBy'] : 'date';
        $orderDir   = isset($_POST['orderDir'])   ? $_POST['orderDir'] : 'DESC';

        // We need to expose our chosen vars back to the view for pagination, this is safe enough because
        // the values have been passed through strip_tags in Application.php anyway
        $this->body['catFilter']  = $categoryNo;
        $this->body['pagNo']      = $pagNo;
        $this->body['$orderBy']   = $orderBy;
        $this->body['$orderDir']  = $orderDir;


        $numPerPage = 5; // 5 posts per page
        $offset = $numPerPage * ($pagNo - 1);

        $this->body['posts'] = $this->model->getPosts($categoryNo, '%' .  $searchTerm . '%', $orderBy, $orderDir, $numPerPage, $offset);


        // If AJAX isn't being used
        if(isset($_POST['ajax']))
        {
            echo json_encode($this->body);
        }
        else
        {
            $this->render('Post', 'index');
        }
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles logic for exposing a specific post's data to the view
     *
     * @param $params       Int of the post to display the details of. Passed to controller by Application.php
     */
    public function view_post($params)
    {
        $post = $this->model->getPost($params[0], isset($_SESSION['userNo']) ? $_SESSION['userNo'] : NULL);

        if (!$post['found'])
        {
            throw new Custom404Exception();
        }
        else
        {
            $this->body['location'] = 'categories';
            $this->body['post'] = $post;
            $this->render('Post', 'view_post');
        }
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles logic for rating posts using AJAX
     */
    public function rate_post()
    {
        if(isset($_SESSION['userNo']) && isset($_POST['rating']))
        {
            $result = $this->model->ratePost($_POST, $_SESSION['userNo']);

            echo json_encode($result);
        }
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles logic for creating posts using AJAX
     */
    public function create_post()
    {
        $this->body['location'] = 'create_post';

        // We check for title instead of submit because AJAX won't pass submit
        if(isset($_POST['title']))
        {
            $validation = $this->model->createPost($_POST, isset($_FILES['imageName']) ? $_FILES['imageName'] : null, $_SESSION['userNo']);

            if($validation['success'])
            {
                // If AJAX isn't being used
                if(isset($_POST['submit']))
                {
                    header('Location: ' . URL_SUB_DIR . '/post/my_posts');
                }
                else
                {
                    $result = array('redirect' => URL_SUB_DIR . '/post/my_posts');

                    echo json_encode($result);
                }
            }
            else
            {
                // If AJAX isn't being used
                if(isset($_POST['submit']))
                {
                    $this->body['error'] = $validation['error'];

                    $this->render('Post', 'create_post');
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
            $this->render('Post', 'create_post');
        }
    }



    /** ------------------------------------------------------------------------------------------------------
     * Populates the $body var with a selective list of posts ready for the view to display the data
     *
     * @param $params       Int of which pagination page to display. Passed to controller by Application.php
     */
    public function my_posts($params)
    {
        // Setup params for pagination with a sane default of page 1 using a unary operator
        $pagNo = isset($params[0]) ? $params[0] : '1';
        $numPerPage = 10;
        $offset = $numPerPage * ($pagNo - 1);

        $this->body['location'] = 'my_posts';
        $this->body['pagNo'] = $pagNo;
        $this->body['posts'] = $this->model->getMyPosts($_SESSION['userNo'], $numPerPage, $offset);

        $this->render('Post', 'my_posts');
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for updating a post using AJAX. Validation and adding to the DB is handed off
     * to the model
     *
     * @param $params       Int of the post to display the details of. Passed to controller by Application.php
     */
    public function edit_post($params)
    {
        $this->body['location'] = 'my_posts';

        if (!isset($params[0]))
        {
            throw new Custom404Exception();
        }

        $post = $this->model->getRowFromTable('post', 'postNo', $params[0]);

        if (!$post['found'])
        {
            throw new Custom404Exception();
        }
        else if ($_SESSION['userNo'] != $post['userNo'])
        {
            throw new Custom403Exception();
        }
        else
        {
            $this->body['post'] = $post;

            // We check for title instead of submit because AJAX won't pass submit
            if(isset($_POST['title']))
            {
                $validation = $this->model->editPost($_POST, $_FILES['imageName'], $params[0], $this->body['post']['imageName']);

                if($validation['success'])
                {
                    // If AJAX isn't being used
                    if(isset($_POST['submit']))
                    {
                        header('Location: ' . URL_SUB_DIR . '/post/my_posts');
                    }
                    else
                    {
                        $result = array('redirect' => URL_SUB_DIR . '/post/my_posts');

                        echo json_encode($result);
                    }
                }
                else
                {
                    // If AJAX isn't being used
                    if(isset($_POST['submit']))
                    {
                        $this->body['error'] = $validation['error'];

                        $this->render('Post', 'create_post');
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
                $this->render('Post', 'edit_post');
            }
        }
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles server side logic for deleting a post using AJAX. Validation and adding to the DB is handed off
     * to the model
     *
     * @param $params       Int of the post to display the details of. Passed to controller by Application.php
     */
    public function delete_post($params)
    {
        $this->body['location'] = 'my_posts';

        if (!isset($params[0]))
        {
            throw new Custom404Exception();
        }

        $post = $this->model->getRowFromTable('post', 'postNo', $params[0]);

        if (!$post['found'])
        {
            throw new Custom404Exception();
        }
        else if ($_SESSION['userNo'] != $post['userNo'])
        {
            throw new Custom403Exception();
        }
        else
        {
            $this->body['post'] = $post;

            if(isset($_POST['submit']))
            {
                try
                {
                    if ($this->model->deletePost($params[0], $post['imageName']))
                    {
                        header('Location: ' . URL_SUB_DIR . '/post/my_posts');
                    }
                }
                catch (\Exception $e)
                {
                    throw $e;
                }
            }
            else
            {
                $this->render('Post', 'delete_post');
            }
        }
    }
}
