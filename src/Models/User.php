<?php

namespace App\Models;

class User {
   /**
   * @var int
   */
  protected $id;

   /**
   * @var string
   */
  protected $first_name;

   /**
   * @var string
   */
  protected $last_name;

   /**
   * @var string
   */
  protected $username;

   /**
   * @var string
   */
  protected $password;

   /**
   * @var string
   */
  protected $email;

  public function __construct() {
    
  }

  /**
   * Get user id
   * 
   * @return int
   */
  public function getId() : int {
    return $this->id;
  }

  /**
   * Sets user id
   * 
   * @param int $id
   */
  public function setId(int $id) : void {
    $this->id = $id;
  }

  /**
   * Get user's first name
   * 
   * @return string
   */
  public function getFirstName() : string {
    return $this->first_name;
  }

  /**
   * Set user's first name
   * 
   * @param string $first_name
   */
  public function setFirstName(string $first_name) : void {
    $this->first_name = $first_name;
  }

  /**
   * Get user's first last
   * 
   * @return string
   */
  public function getLastName() : string {
    return $this->last_name;
  }

  /**
   * Set user's last name
   * 
   * @param string $last_name
   */
  public function setLastName(string $last_name) : void {
    $this->last_name = $last_name;
  }

  /**
   * Get user's username
   * 
   * @return string
   */
  public function getUsername() : string {
    return $this->username;
  }

   /**
   * Set user's username
   * 
   * @param string $username
   */
  public function setUsername(string $username) : void {
    $this->username = $username;
  }

   /**
   * Get user's password
   * 
   * @return string
   */
  public function getPassword() : string {
    return $this->password;
  }

  /**
   * Set user's password
   * 
   * @param string $password
   */
  public function setPassword(string $password) : void {
    $this->password = $password;
  }

  /**
   * Get user's email
   * 
   * @return string
   */
  public function getEmail() : string {
    return $this->email;
  }

  /**
   * Set user's email
   * 
   * @param string $email
   */
  public function setEmail($email) : void {
    $this->email = $email;
  }
}
