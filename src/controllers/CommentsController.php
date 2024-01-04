<?php

namespace Controllers;

use Core\BaseController;
use Models\Comment;
use Core\Application;
use DAO\CommentsDAO;
use DAO\usersDAO;
use DAO\RatingsDAO;
use Models\User;
use Models\Rating;


/**
 * Class InfoController
 *
 * This class is a constructor method for the InfoController class, which extends the BaseController class.
 * It calls the parent constructor to inherit the properties and methods of the BaseController class.
 */
class CommentsController extends BaseController {

    private $commentDAO;
    private $userDAO;
    private $ratingsDAO;

    /**
     * InfoController constructor.
     *
     * @param object $container The container object.
     * @param array $routeParams The route parameters.
     * 
     */
    public function __construct($container, $routeParams)
    {
        //call the parent constructor to get access to the properties and methods of the BaseController class
        parent::__construct(...func_get_args());
        $this->commentDAO = new CommentsDAO();
        $this->userDAO = new usersDAO();
        $this->ratingsDAO = new ratingsDAO();
    }

 

    /**
     * Creates a new comment by processing the request body, validating the comment data,
     * registering the comment in the database, and sending a response.
     *
     * @return void
     */
    public function createComment(){
        $body = (object) $this->request->getBody();
        $body->user = Application::$app->user;
        $body->created_at = date("Y-m-d H:i:s");
        $comment = new Comment();
        $comment->loadData($body);
        if(!$comment->validate()){
            Application::$app->session->setFlash('error', "Comment data is not valid");
        }
        try {
            $stmt = $this->commentDAO->register($comment);
            $stmt = $stmt->get();
        } catch (\Throwable $th) {
            Application::$app->session->setFlash('error', "an error occurred while creating the comment");
        }
        $content = $comment->render();
        $this->response->setStatusCode(200);
        $this->response->setContent($content);
        $this->response->send();
    }

    /**
     * Retrieves comments from the database, creates Comment objects, and renders them into a string.
     * Sets the response status code, content, and sends the response.
     *
     * @param int $id The ID of the movie review from which to retrieve the comments.
     * @return void
     */
    public function getComments($id){
        $comments_data= $this->commentDAO->getComments($id, 5, desc: false);
        
        // Check if comments_data is empty
        if(empty($comments_data)) {
            $this->response->setStatusCode(200);
            $this->response->setContent("");
            $this->response->send();
            return;
        }
        
        $comments = [];
        $content = "";
        foreach ($comments_data as $comment_data) {
            $comment = new Comment();
            $comment->set_id($comment_data->id);
            $comment->set_comment($comment_data->comment);
            $comment->set_created_at($comment_data->created_at);
            $comment->set_user($this->userDAO->find($comment_data->user_id, User::class));
            $comment->set_rating($this->ratingsDAO->find($comment_data->rating_id, Rating::class));
            $comments[] = $comment;
        }
        foreach ($comments as $comment) {
            $content .= $comment->render();
        }
        $this->response->setStatusCode(200);
        $this->response->setContent($content);
        $this->response->send();
    }
}
?>