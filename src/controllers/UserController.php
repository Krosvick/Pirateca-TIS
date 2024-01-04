<?php

namespace Controllers;

use DAO\UsersDAO;
use Core\BaseController;
use DAO\moviesDAO;
use DAO\RatingsDAO;
use Models\User;
use Core\Application;
use Core\Middleware\AuthMiddleware;

/**
 * UserController class
 *
 * This class is responsible for handling user-related functionality in the application.
 */
class UserController extends BaseController{
    private $userDAO;
    public $user;

    /**
     * UserController constructor
     *
     * Initializes the UserController class by calling the parent constructor and setting the user and userDAO properties.
     *
     * @param object $container An object representing the application's container.
     * @param array $routeParams An array containing the route parameters.
     * @return void
     */
    public function __construct($container, $routeParams) {
        parent::__construct(...func_get_args());
        $this->user = Application::$app->session->get('user') ?? null;
        $this->userDAO = new UsersDAO();
        $this->registerMiddleware(new AuthMiddleware(['logout', 'follow']));
    }

    /**
     * LikedMovies method
     *
     * Renders a page that displays the movies liked by a user.
     *
     * @return void The rendered HTML content of the "likedpost" template.
     */
    public function likedMovies($id){
        $ratingsDAO = new RatingsDAO();
        $MoviesDAO = new MoviesDAO();

        $isLogged = !Application::isGuest();
        $loggedUserId = $isLogged ? $this->user->get_id() : null;

        if($isLogged && $loggedUserId == $id){
            $username = $this->user->get_username();
            $user_movies = $this->user->get_liked_movies($ratingsDAO, $MoviesDAO, 10);
            $userData = $this->user;
        }else{
            $userData = $this->userDAO->find($id, User::class);
            $user_movies = $userData->get_liked_movies($ratingsDAO, $MoviesDAO, 15);
            $username = $userData->get_username();
        }
        $data = [
            'user_movies' => $user_movies,
            'username' => $username,
            'userData' => $userData,
        ];
        $metadata = [
            'title' => 'Pirateca - Profile',
            'description' => 'This is the profile page of the user.',
            'cssFiles' => [
                '' // TODO: add css files here
            ],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];

        return $this->render("likedpost", $optionals);
    }

    /**
     * Retrieves user profile data from the database and renders the 'profile' view.
     *
     * @param int $id The ID of the user profile to retrieve.
     * @return void
     */
    public function profilePage($id)
    {
        $userProfileData = User::findOne($id);

        $data = [
            'loggedUser' => $this->user,
            'userProfileData' => $userProfileData,
        ];
        $metadata = [
            'title' => 'Pirateca - Profile',
            'description' => 'This is the profile page of the user.',
            'cssFiles' =>  ['styles_nav.css'],
        ];
        $optionals = [
            'data' => $data,
            'metadata' => $metadata
        ];

        return $this->render('profile', $optionals);
    }

    /**
     * Logout method
     *
     * Logs out the user by clearing the user session and redirecting the user to the homepage.
     *
     * @return void
     */
    public function logout(){
        if($this->user){
            Application::$app->logout();
            header('Location: /');
        }
        else{
            header('Location: /');
        }
    }

    /**
     * Follows a user and redirects to their profile page.
     *
     * This method adds a follower to a user and redirects to the profile page of the user being followed.
     *
     * @param int $id The ID of the user to be followed.
     * @return void
     */
    public function follow($id){
        $this->userDAO->add_follower($id, $this->user->get_id());
        $this->response->redirect("/profile/$id");
    }

    /**
     * Method to handle the action of liking a review.
     *
     * Calls the `like_review` method of the `userDAO` object to perform the like operation.
     * If an exception occurs, sets the response status code to 500 and sends an error message as the response content.
     * Retrieves the review ID and renders a partial view called `like-button.php` using the extracted data.
     * Sets the response status code to 200 and sends the rendered content as the response.
     *
     * @param int $id The ID of the review to be liked.
     * @return void
     */
    public function likeReview($id)
    {
        try {
            $this->userDAO->like_review($id, $this->user->get_id());
        } catch (\Exception $e) {
            $this->response->setStatusCode(500);
            $this->response->setContent($e->getMessage());
            $this->response->send();
        }
        $data = [
            'review_id' => $id,
        ];
        ob_start();
        extract($data);
        require(base_path("/src/views/partials/like-button.php"));
        $content = ob_get_clean();
        $this->response->setStatusCode(200);
        $this->response->setContent($content);
        $this->response->send();
    }

    /**
     * Dislikes a review.
     *
     * This method handles the action of disliking a review. It calls the `unlike_review` method of the `userDAO` object to perform the dislike operation. If an exception occurs, it sets the response status code to 500 and sends an error message as the response content. It then retrieves the review ID and renders a partial view called `unlike-button.php` using the extracted data. Finally, it sets the response status code to 200 and sends the rendered content as the response.
     *
     * @param int $id The ID of the review to be disliked.
     * @return void
     */
    public function dislikeReview($id){
        try{
            $this->userDAO->unlike_review($id, $this->user->get_id());
        }catch(\Exception $e){
            $this->response->setStatusCode(500);
            $this->response->setContent($e->getMessage());
            $this->response->send();
        }
        $data = [
            'review_id' => $id,
        ];
        ob_start();
        extract($data);
        require(base_path("/src/views/partials/unlike-button.php"));
        $content = ob_get_clean();
        $this->response->setStatusCode(200);
        $this->response->setContent($content);
        $this->response->send();
    }

    /**
     * Retrieves the like status of a review for the currently logged-in user and renders a partial view accordingly.
     *
     * @param int $id The ID of the review to check the like status for.
     * @return void
     */
    public function getLikeReview($id){
        try{
            $like = $this->userDAO->get_like_review($id, $this->user->get_id());
        }catch(\Exception $e){
            return;
        }
        if(!$like){
            $data = [
                'review_id' => $id,
            ];
            ob_start();
            extract($data);
            require(base_path("/src/views/partials/unlike-button.php"));
            $content = ob_get_clean();
            $this->response->setStatusCode(200);
            $this->response->setContent($content);
            $this->response->send();
            return;
        }
        $data = [
            'like' => $like,
            'review_id' => $like[0]->rating_id,
        ];
        ob_start();
        extract($data);
        require(base_path("/src/views/partials/like-button.php"));
        $content = ob_get_clean();
        $this->response->setStatusCode(200);
        $this->response->setContent($content);
        $this->response->send();
    }
}
