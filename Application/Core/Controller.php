<?php
/**
 * This is the controller superclass, primarily consisting of the render and sanitise methods.
 *
 * Date: 1st February 2018
 * @author: Anna Thomas - s4927945
 * Assignment 2 - Wiki
 */
class Controller
{
    protected $model;
    protected $body = array();
    protected $methodPermissions = array(USER_GUEST  => array(), USER_AUTHED => array());

    /** ------------------------------------------------------------------------------------------------------
     * Setup model for this controller
     *
     * @param $model    Instantiate the associated model for this controller
     */
    function __construct($model)
    {
        $this->model = $model;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Returns the current permission for the given controller
     *
     * @param $method   The method we wish to check the permissions for
     *
     * @return          Boolean defining whether or not we have permission to view the method
     */
    public function getPermission($method)
    {

        // Check for a session value and override our $userLevel variable accordingly.
        if (isset($_SESSION['userLevel']))
        {
            $userLevel = $_SESSION['userLevel'];
        }
        else
        {
            // We need to set a default value in case this is the first time the user has been on the site and
            // thus has no session stored
            $userLevel = USER_GUEST;
        }

        // Search the array which corresponds to our user level (inside the multidimensional methodPermissions
        // array) for the existence of the method we're attempting to access. Because we adopt a default view
        // permission of false as best practice, it must exist for us to have access to it.
        return in_array($method, $this->methodPermissions[$userLevel]);
    }



    /** ------------------------------------------------------------------------------------------------------
     * Handles the main rendering of each page by adding the header and footer template includes either side
     * of the main view file being rendered. This function also sets default components of the $body variable
     * that views utilise as well as recursively sanitising the $body var prior to it being exposed to views.
     *
     * @param $viewDir       String of the subdirectory containing the view file inside the View folder
     * @param $viewFile      String of the view file inside the view subdirectory
     */
    protected function render($viewDir, $viewFile)
    {
        // Categories are always exposed to the view
        $this->body['categories'] = $this->getCategories();

        if (isset($_SESSION['username']))
        {
            $this->body['username'] = $_SESSION['username'];
            $this->body['userLevel'] = $_SESSION['userLevel'];
            $this->body['userNo'] = $_SESSION['userNo'];
        }
        else
        {
            $this->body['username'] = 'Guest';
            $this->body['userLevel'] = USER_GUEST;
            $this->body['userNo'] = 0;
        }

        // Sanitise the $body array using the recursive sanitise() method before exposing data to the view
        $body = $this->sanitise($this->body);

        require VIEW_DIR . 'Templates' . DS . 'header.php';
        require VIEW_DIR .  $viewDir   . DS .  $viewFile . '.php';
        require VIEW_DIR . 'Templates' . DS . 'footer.php';

        unset($body);
    }



    /** ------------------------------------------------------------------------------------------------------
     * The method below orders subcategories into a multidimensional array of categories
     * Because assignment brief requires posts to have categories and subcategories we need to re-order
     * the categories
     *
     * categoryNo  name               parentNo    |   Notes
     *
     * 1	        Home and Leisure	  0           |   Category, because its parentNo is 0
     * 2	        Computing           0           |
     * 3	        Gardening           1           |   Subcategory of Home & Leisure because its parentNo is 1
     * 4	        Homeware            1           |
     * 5	        Kitchen             1           |
     * 6	        DIY                 1           |
     * 7	        Security            2           |   Subcategory of Computing because its parentNo 2
     * 8	        Web                 2           |
     * 9	        Linux               2           |
     *
     * The benefits of this category/subcategory storing method means we can have infinite subcategories e.g:
     *
     * 10          Ubuntu             9           |   Subcategory of the Computing -> Linux Subcategory
     *
     * Subcategories of subcategories are out of scope in the assignment and as such not accounted for in the
     * method below, however implementing it would be relatively trivial.
     */
    private function getCategories()
    {
        $result = array();
        $categories  = $this->model->getCategories();

        foreach ($categories as $cat)
        {
            if (is_array($cat))
            {
                if ($cat['parentNo'] == 0)
                {
                    $result[$cat['categoryNo']] = $cat;
                }
                else
                {
                    $result[$cat['parentNo']][] = $cat;
                }
            }
            else
            {
                $result['found'] = $cat;
            }
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * This method does HTML entity encoding to data exposed to views through the body variable.
     * This function is recursive and designed to handle multi-dimensional arrays.
     *
     * @param  $input     Array of content to be sanitised recursively
     *
     * @return $input     The now htmlentities encoded value(s).
     */
    private function sanitise($input)
    {
        if (is_array($input))
        {
            // Foreach array
            foreach ($input as $index => $value)
            {
                $input[$index] = $this->sanitise($value);
            }
        }
        else if (is_string($input))
        {
            $input = htmlentities($input);
        }

        return $input;
    }
}
