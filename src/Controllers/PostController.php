<?php

namespace App\Controllers;

use App\Models\IDbAdapter;
use App\Models\Post;
use Monolog\Logger;

class PostController {
  /**
   * @var \App\Models\IDbAdapter
   */
  protected $db;

   /**
   * @var Logger
   */
  protected $log;

  /**
   * @param IDbAdapter $dbAdapter
   */
  public function __construct(IDbAdapter $dbAdapter, Logger $log) {
    $this->db = $dbAdapter;
    $this->log = $log;
  }

  /**
   * Fetch all posts
   * 
   * @return array
   */
  public function fetchAll() : array {
    $sql = 'SELECT p.id, p.post_title, p.post, p.author_id, u.username as author_name FROM posts p INNER JOIN users u ON p.author_id = u.id';
    $postRecords = $this->db->fetchAll($sql);

    $posts = array();

    if(count($postRecords) > 0) {
      foreach($postRecords as $postRecord) {
        $post = array(
          'id' => $postRecord['id'], 
          'post_title' => $postRecord['post_title'], 
          'author_id' => $postRecord['author_id'], 
          'author_name' => $postRecord['author_name'], 
          'post' => $postRecord['post']
        );

        $posts[] = $post;
      }
    }

    return $posts;
  }

  /**
   * Fetch a posts by id
   * 
   * @param $id
   * 
   * @return array
   */
  public function fetchById(int $id) : array {
    $sql = 'SELECT p.id, p.post_title, p.post, p.author_id, u.username as author_name FROM posts p INNER JOIN users u ON p.author_id = u.id WHERE p.id =?';
    $postRecord = $this->db->fetchOne($sql, [$id]);

    if($postRecord) {
      $post = array(
        'id' => $postRecord['id'], 
        'post_title' => $postRecord['post_title'], 
        'author_id' => $postRecord['author_id'], 
        'author_name' => $postRecord['author_name'], 
        'post' => $postRecord['post']
      );

      return $post;
    }

    return null;
  }

  /**
   * Insert a post record
   * 
   * @param Post $post
   * 
   * @return array
   */
  public function insert(Post $post) : array {
    $postRecord = [
      'post_title' => $post->getPostTitle(),
      'author_id'  => $post->getAuthorId(),
      'post'       => $post->getPost()
    ];

    $id = $this->db->insert('posts', $postRecord);

    return array(
      'id' => $id, 
      'author_id' => $post->getAuthorId(), 
      'post_title' => $post->getPostTitle(), 
      'post' => $post->getPost()
    );
  }

    /**
   * Update a post
   * 
   * @param Post $post
   * 
   * @return array
   */
  public function update(Post $post) : array {
    $postRecord = [
      'post_title' => $post->getPostTitle(),
      'author_id'  => $post->getAuthorId(),
      'post'       => $post->getPost()
    ];

    $this->db->update('posts', $postRecord, ['id' => $post->getId()]);

    return array(
      'id' => $post->getId(), 
      'author_id' => $post->getAuthorId(), 
      'post_title' => $post->getPostTitle(), 
      'post' => $post->getPost()
    );
  }

  /**
   * Delete a post
   * 
   * @param int $id
   */
  public function delete(int $id) : void {
    $this->db->delete('posts', ['id' => $id]);
  }
}
