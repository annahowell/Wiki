<?php
require_once APP_DIR . '/Core/Model.php';

/**
 * Post model, expands upon the base model's functionality to validate posts and ratings and handle posts and
 * ratings to and from the database.
 *
 * Date: 10th February 2018
 * @author: Anna Thomas - s4927945
 * Assignment 2 - Wiki
 */
class PostModel extends Model
{

    /** ------------------------------------------------------------------------------------------------------
     * Queries the post table of the database to return limited ordered posts by search term
     *
     * @param $categoryNo   The category to search
     * @param $searchTerm   The term to search for
     * @param $orderBy      The column to order by
     * @param $orderDir     The direction to order by
     * @param $limit        The limit of results to return
     * @param $offset       The offset to begin the return of results from
     *
     * @return $rows        The data returned by the query along with a found boolean and potential page count
     */
    public function getPosts($categoryNo, $searchTerm, $orderBy, $orderDir, $limit, $offset)
    {
        // We'll do some further sanity checks on values passed, remembering that the entire url has already
        // been run through strip_tags in Application.php anyway

        if (!is_numeric($categoryNo))
        {
            $categoryNo = '%';
        }

        if ($searchTerm == '' || $searchTerm == '*' || strtolower($categoryNo) == 'all' || strtolower($categoryNo) == 'everything')
        {
            $searchTerm = '%';
        }

        if (!in_array($orderBy, array('displayName', 'dateModified', 'rating')))
        {
            $orderBy = 'dateModified';
        }

        if ($orderDir != 'ASC')
        {
            $orderDir = 'DESC';
        }

        $sql = "SELECT p.postNo, p.userNo, p.title, p.content, p.imageName, p.dateModified, u.displayName, c2.name AS catParent, c.name AS catName, COALESCE(r.rating, 0) AS rating
                  FROM `post` p INNER JOIN `category` c  ON p.categoryNo = c.categoryNo
                                INNER JOIN `category` c2 ON c.parentNo = c2.categoryNo
                                INNER JOIN `user` u      ON p.userNo = u.userNo
                                 LEFT JOIN (SELECT `postNo`, AVG(rating) AS rating from `rating` GROUP BY `postNo`) r
                                        ON p.postNo = r.postNo
                                     WHERE (p.categoryNo LIKE :category OR c.parentNo LIKE :category)
                                       AND (p.title      LIKE :searchTerm
                                        OR c.name        LIKE :searchTerm
                                        OR p.content     LIKE :searchTerm)
                                  ORDER BY $orderBy $orderDir LIMIT $limit OFFSET $offset";

        $query = $this->db->prepare($sql);

        $query->bindParam(':category',   $categoryNo, PDO::PARAM_STR);
        $query->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);

        $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($rows)
        {
            $sql = "SELECT COUNT(*)
                  FROM `post` p INNER JOIN `category` c  ON p.categoryNo = c.categoryNo
                                INNER JOIN `category` c2 ON c.parentNo = c2.categoryNo
                                INNER JOIN `user` u      ON p.userNo = u.userNo
                                 LEFT JOIN (SELECT `postNo`, AVG(rating) AS rating from `rating` GROUP BY `postNo`) r
                                        ON p.postNo = r.postNo
                                     WHERE (p.categoryNo LIKE :category OR c.parentNo LIKE :category)
                                       AND (p.title      LIKE :searchTerm
                                        OR c.name        LIKE :searchTerm
                                        OR p.content     LIKE :searchTerm)";
            $query = $this->db->prepare($sql);

            $query->bindParam(':category', $categoryNo,   PDO::PARAM_STR);
            $query->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);

            $query->execute();

            $totalNumberOfMyPosts = $query->fetchColumn();

            $rows['tor'] = $totalNumberOfMyPosts;

            $rows['pagCount'] = ceil($totalNumberOfMyPosts / $limit);
            $rows['found'] = TRUE;
        }
        else
        {
            $rows['found'] = FALSE;
        }

        return $rows;
    }


    /** ------------------------------------------------------------------------------------------------------
     * Queries the post table of the database to get a single post by postNo with an average rating along with
     * any rating the current user may have already made
     *
     * @param $postNo       The postNo of the post to get
     * @param $userNo       The userNo of the user viewing the post
     *
     * @return $row         The data returned by the query along with a found boolean
     */
    public function getPost($postNo, $userNo)
    {

        $sql = "SELECT p.postNo, p.userNo, p.title, p.content, p.imageName, p.dateModified, u.displayName, c2.name AS catParent, c.name AS catName, AVG(r.rating) AS average,
               (SELECT ur.rating FROM `rating` ur WHERE ur.postNo = :postNo AND ur.userNo = :userNo) AS userRating
                  FROM `post` p INNER JOIN `category` c  ON p.categoryNo = c.categoryNo
                                INNER JOIN `category` c2 ON c.parentNo = c2.categoryNo
                                INNER JOIN `user` u      ON p.userNo = u.userNo
                                INNER JOIN `rating` r    ON p.postNo = r.postNo
                                                      WHERE p.postNo = :postNo";

        $query = $this->db->prepare($sql);
        $query->bindParam(':postNo', $postNo, PDO::PARAM_INT);
        $query->bindParam(':userNo', $userNo, PDO::PARAM_INT);

        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        if (!is_null($row['postNo']))
        {
            $row['found'] = TRUE;
        }
        else
        {
            $row['found'] = FALSE;
        }

        return $row;
    }


    /** ------------------------------------------------------------------------------------------------------
     * Returns a list of orders from the posts table along with a few details of the ordering user
     *
     * @param $userNo    The userNo of the logged in user
     * @param $limit     Total number of results to return
     * @param $offset    Offset from which the results are returned
     *
     * @return $rows     Rows (if found) from a given table along with a 'found' boolean declaring outcome
     */
    public function getMyPosts($userNo, $limit, $offset)
    {
        $sql = "SELECT p.postNo, p.title, p.dateModified, p.userNo, r.average, c.name AS catName FROM `post` p LEFT JOIN
               (SELECT `postNo`, AVG(rating)  AS average from `rating` GROUP BY `postNo`) r
                    ON p.postNo = r.postNo INNER JOIN `category` c ON p.categoryNo = c.categoryNo
                    WHERE p.userNo = :userNo ORDER BY `dateModified` DESC LIMIT :limit OFFSET :offset";

        $query = $this->db->prepare($sql);
        $query->bindParam(':userNo', $userNo, PDO::PARAM_INT);
        $query->bindParam(':limit',  $limit,  PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);

        $query->execute();

        $rows = $query->fetchAll(PDO::FETCH_ASSOC);

        if ($rows)
        {
            $sql = "SELECT COUNT(*) FROM `post` WHERE `userNo` = $userNo";
            $query = $this->db->prepare($sql);

            $query->execute();

            $totalNumberOfMyPosts = $query->fetchColumn();

            $rows['pagCount'] = ceil($totalNumberOfMyPosts / $limit);
            $rows['found'] = TRUE;
        }
        else
        {
            $rows['found'] = FALSE;
        }
        return $rows;
    }


    /** ------------------------------------------------------------------------------------------------------
     * Handles the logic for adding a post
     *
     * @param  $postData      Post data sent by the form
     * @param  $file          File data sent by the form
     * @param  $userNo        The userNo of the logged in user adding the post
     *
     * @return $result        Array containing success boolean, and any error messages from validation method
     */
    public function createPost($postData, $file, $userNo)
    {
        $result = $this->validatePost($postData, $file);

        if ($result['success'])
        {
            $dateModified = date('Y-m-d H:i:s');

            $sql = 'INSERT INTO `post` (title, content, imageName, dateModified, userNo, categoryNo)
                    VALUES (:title, :content, :imageName, :dateModified, :userNo, :categoryNo)';

            $query = $this->db->prepare($sql);
            $query->bindParam(':title',        $postData['title'],      PDO::PARAM_STR);
            $query->bindParam(':content',      $postData['content'],    PDO::PARAM_STR);
            $query->bindParam(':imageName',    $result['imageName'],    PDO::PARAM_STR);
            $query->bindParam(':dateModified', $dateModified,           PDO::PARAM_STR);
            $query->bindParam(':userNo',       $userNo,                 PDO::PARAM_INT);
            $query->bindParam(':categoryNo',   $postData['categoryNo'], PDO::PARAM_INT);

            $query->execute();
        }

        return $result;
    }


    /** ------------------------------------------------------------------------------------------------------
     * Handles the logic for updating an existing post
     *
     * @param  $postData            Post data sent by the form
     * @param  $file                File data sent by the form
     * @param  $PostNo              The post number of the post we're updating
     * @param  $existingImageName   The name of the image currently being used for this post
     *
     * @return $result              Array containing success boolean, and any error messages from the
     *                              validation method
     */
    public function editPost($postData, $file, $postNo, $existingImageName)
    {
        $result = $this->validatePost($postData, $file, False); // This is not a new post

        if ($result['success'])
        {
            $dateModified = date('Y-m-d H:i:s');

            $sql = 'UPDATE `post` SET title = :title,
                                    content = :content,
                               dateModified = :dateModified,
                                 categoryNo = :categoryNo,
                                  imageName = :imageName WHERE postNo = :postNo';

            $query = $this->db->prepare($sql);
            $query->bindParam(':title',        $postData['title'],      PDO::PARAM_STR);
            $query->bindParam(':content',      $postData['content'],    PDO::PARAM_STR);
            $query->bindParam(':dateModified', $dateModified,           PDO::PARAM_STR);
            $query->bindParam(':categoryNo',   $postData['categoryNo'], PDO::PARAM_INT);
            $query->bindParam(':postNo',       $postNo,                 PDO::PARAM_INT);

            if (isset($result['imageName']))
            {
                $query->bindParam(':imageName', $result['imageName'], PDO::PARAM_STR);

                $this->removeImageFile($existingImageName);
            }
            else
            {
                $query->bindParam(':imageName', $existingImageName, PDO::PARAM_STR);
            }

            $query->execute();
        }

        return $result;
    }


    /** ------------------------------------------------------------------------------------------------------
     * Deletes a post from the post table, along with it's associated image file
     *
     * @param  $postNo              The id number of the post to be deleted
     * @param  $imageNameToDelete   The name of the file to be deleted upon success of removal from post table
     *
     * @return $result              A boolean containing the success or failure of the deletion attempt
     */
    public function deletePost($postNo, $imageNameToDelete)
    {
        // FK has ON DELETE CASCADE so deleting posts removes ratings too
        $result = $this->deleteRowFromTable('post', 'postNo', $postNo);

        if ($result)
        {
            // Delete image
            $this->removeImageFile($imageNameToDelete);
        }

        return $result;
    }


    /** ------------------------------------------------------------------------------------------------------
     * Provides simple validation for the amendment of posts
     *
     * @param  $postData    Post data sent by the calling function
     * @param  $userNo      UserNo of the user rating the post
     *
     * @return $result      A success boolean of the validation along with any error message generated
     */
    public function ratePost($postData, $userNo)
    {
        $result = $this->validateRating($postData);

        if ($result['success'])
        {
            $this->db->beginTransaction();

            // Update the desired rating
            $sql = 'INSERT INTO rating (userNo, postNo, rating) VALUES (:userNo, :postNo, :rating) ON DUPLICATE KEY UPDATE rating = :rating';

            $query = $this->db->prepare($sql);
            $query->bindParam(':userNo', $userNo,             PDO::PARAM_INT);
            $query->bindParam(':postNo', $postData['postNo'], PDO::PARAM_INT);
            $query->bindParam(':rating', $postData['rating'], PDO::PARAM_INT);

            $query->execute();

            // Return the new average rating for this post so it can be updated to the page on the fly
            $sql = "SELECT AVG(rating) AS average FROM rating WHERE postNo = :postNo";

            $query = $this->db->prepare($sql);
            $query->bindParam(':postNo', $postData['postNo'], PDO::PARAM_INT);
            $query->execute();

            $newAverage = $query->fetch(PDO::FETCH_ASSOC);

            $this->db->commit();

            return number_format($newAverage['average'], 1, '.', '');
        }
        else
        {
            return $result;
        }
    }

    /** ------------------------------------------------------------------------------------------------------
     * Provides simple validation for the amendment of posts
     *
     * @param  $postData    Post data sent by the calling function
     * @param  $file        File data sent by the calling function
     * @param  $newPost     Whether or not this is the validation of a new post
     *
     * @return $result      A success boolean of the validation along with any error message generated and the
     *                      image name for the post
     */
    private function validatePost($postData, $file, $newPost = TRUE)
    {
        $result['success'] = FALSE;

        if (isset($postData['categoryNo']) && is_numeric($postData['categoryNo']))
        {
            $category = $this->getRowFromTable('category', 'categoryNo', $postData['categoryNo']);
        }
        else
        {
            $category['found'] = FALSE;
        }

        // We're using php 5.3.3 so we'll use trim($foo) === false instead of empty(trim($foo))
        if (trim($postData['title']) == FALSE)
        {
            $result['error'] = 'Please enter a valid title';
        }
        else if (trim($postData['content']) == FALSE)
        {
            $result['error'] = 'Please enter some post content';
        }
        else if (!$category['found'])
        {
            $result['error'] = 'Please enter a valid category';
        }
        else
        {
            if (is_uploaded_file($file['tmp_name']))
            {
                $result = $this->handleImageUpload($file);
            }
            else if (!$newPost)
            {
                $result['success'] = TRUE;
            }
            else
            {
                $result['imageName'] = PLACEHOLDER_IMG; // Found in config.php
                $result['success'] = TRUE;
            }
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Provides simple validation for adding ratings to posts
     *
     * @param  $postData    Post data sent by the calling function
     *
     * @return $result      A success boolean of the validation along with any error message generated
     */
    private function validateRating($postData)
    {
        $result['success'] = FALSE;

        // Check the post we're attempting to rate exists
        if (isset($postData['postNo']) && is_numeric($postData['postNo']))
        {
            $post = $this->getRowFromTable('post', 'postNo', $postData['postNo']);
        }
        else
        {
            $post['found'] = FALSE;
        }

        if (!$post['found'])
        {
            $result['error'] = 'The post you attempted to rate does not exist';
        }
        else if (!isset($postData['rating']) || !is_numeric($postData['rating']) || $postData['rating'] < 1 || $postData['rating'] > 5)
        {
            $result['error'] = 'Please enter a valid rating';
        }
        else
        {
            $result['success'] = TRUE;
        }

        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Provides simple file upload handling for post images
     *
     * @param  $file        File data sent by the calling function
     *
     * @return $result      A success boolean of the validation along with any error message generated and the
     *                      image name generated by the file saving process.
     */
    private function handleImageUpload($file)
    {
        $result['success'] = FALSE;
        $allowedExtensions = array('png', 'jpg', 'jpeg');

        $explodedName = explode('.', $file['name']);
        $fileExtension = strtolower(end($explodedName));
        $imageName = mt_rand(1000000000, 9999999999) . '.' . $fileExtension;

        if (!in_array($fileExtension, $allowedExtensions))
        {
            $result['error'] = 'Invalid file extension';
        }
        else if ($file['size'] > 3072000)
        {
            $result['error'] = 'File size is larger than 3 meg';
        }
        else if(!move_uploaded_file($file['tmp_name'], IMAGE_DIR . $imageName))
        {
            $result['error'] = 'An unhandled error occurred';
        }
        else
        {
            $result['imageName'] = $imageName;
            $result['success'] = TRUE;
        }
        return $result;
    }



    /** ------------------------------------------------------------------------------------------------------
     * Deletes an image file from disk if the filename passed isn't the placeholder image name
     *
     * @param  $imageNameToDelete    The name of the file to be deleted from disk
     */
    private function removeImageFile($imageNameToDelete)
    {
        if ($imageNameToDelete != PLACEHOLDER_IMG)
        {
            @unlink(IMAGE_DIR . $imageNameToDelete);
        }
    }
}
