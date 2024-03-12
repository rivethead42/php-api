<?php

namespace App\Models;

class Post {
  /**
   * @var int
   */
  protected $id;

  /**
   * @var string
   */
  protected $post_title;

   /**
   * @var int
   */
  protected $author_id;

   /**
   * @var string
   */
  protected $author;

   /**
   * @var string
   */
  protected $post;

  public function __construct() {

  }

  /**
   * Get post id
   * 
   * @return int
   */
  public function getId() : int {
    return $this->id;
  }

  /**
   * Sets post id
   * 
   * @param int $id
   */
  public function setId(int $id) : void {
    $this->id = $id;
  }

  /**
   * Get post title
   * 
   * @return string
   */
  public function getPostTitle() : string {
    return $this->post_title;
  }

  /**
   * Sets post title
   * 
   * @param string $post_title
   */
  public function setPostTitle(string $post_title) : void {
    $this->post_title = $post_title;
  }

  /**
   * Get post author id
   * 
   * @return int
   */
  public function getAuthorId() : int {
    return $this->author_id;
  }

  /**
   * Sets post author id
   * 
   * @param int $author_id
   */
  public function setAuthorId(int $author_id) : void {
    $this->author_id = $author_id;
  }

  /**
   * Get post author
   * 
   * @return string
   */
  public function getAuthor() : string {
    return $this->author;
  }

  /**
   * Sets post author id
   * 
   * @param string $author
   */
  public function setAuthor(string $author) : void {
    $this->author = $author;
  }

  /**
   * Get post body
   * 
   * @return string
   */
  public function getPost() : string {
    return $this->post;
  }

  /**
   * Sets post body
   * 
   * @param string $post
   */
  public function setPost(string $post) : void {
    $this->post = $post;
  }
}
