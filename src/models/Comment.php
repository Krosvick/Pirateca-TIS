<?php

namespace Models;

use Core\Model;
use Models\User;
use GuzzleHttp\Client;

/**
 * 
 */
class Comment extends Model{

    private ?int $id;
    private ?User $user;
    private ?Rating $rating;
    private ?string $comment;
    private $created_at;

   
    /**
     * Comment constructor.
     * Initializes the properties of the Comment object with the provided values.
     * @param int|null $id The ID of the comment.
     * @param User|null $user The user associated with the comment.
     * @param Rating|null $rating The rating associated with the comment.
     * @param string|null $comment The comment.
     */
    public function __construct(int $id = null, User $user = null, Rating $rating = null, string $comment = null, $created_at = null)
    {
        $this->id = $id;
        $this->user = $user;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->created_at = $created_at;
    }

 
    public function get_id()
    {
        return $this->id;
    }

    /**
     * Set the ID of the comment.
     *
     * @param int $id The ID of the comment.
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * Get the user associated with the comment.
     *
     * @return User The user associated with the comment.
     */
    public function get_user()
    {
        return $this->user;
    }

    /**
     * Set the user associated with the comment.
     *
     * @param User $user The user associated with the comment.
     */
    public function set_user(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the rating associated with the comment.
     *
     * @return Rating The rating associated with the comment.
     */
    public function get_rating()
    {
        return $this->rating;
    }

    /**
     * Set the rating associated with the comment.
     *
     * @param Rating $rating The rating associated with the comment.
     */

    public function set_rating(Rating $rating)
    {
        $this->rating = $rating;
    }

    /**
     * Get the comment.
     *
     * @return string The comment.
     */

    public function get_comment()
    {
        return $this->comment;
    }

    /**
     * Set the comment.
     *
     * @param string $comment The comment.
     */

    public function set_comment($comment)
    {
        $this->comment = $comment;
    }

    public function get_created_at()
    {
        return $this->created_at;
    }

    public function set_created_at($created_at)
    {
        $this->created_at = $created_at;
    }

    public function get_user_id(){
        return $this->user->get_id();
    }

    public function get_review_id(){
        return $this->rating->get_id();
    }

    /**
     * Get the primary key of the Rating model.
     *
     * @return string The primary key of the Rating model.
     */
    public static function primaryKey()
    {
        return 'id';
    }
    /**
     * Get the validation rules for the 'original_title' attribute.
     *
     * @return array The validation rules for the 'original_title' attribute.
     */
    public function rules(){
        return [
            'comment' => [self::RULE_MAX, 'max' => 1000],
        ];
    }
    /**
     * Get all the attributes of the 'Movie' class.
     *
     * @return array All the attributes of the 'Movie' class.
     */
    public function attributes(){
        return [
            'comment',
            'created_at',
            'rating_id',
            'user_id',
        ];
    }

    public function render(){
        $data = [
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'user' => $this->user,
        ];
        ob_start();
        extract($data);
        require(base_path("src/views/partials/comment.php"));
        $output = ob_get_clean();
        return $output;
    }

    
}