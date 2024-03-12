<?php

namespace App\Controllers;

use App\Models\IDbAdapter;
use App\Models\User;
use App\Models\Emailer;
use Amp\Future;
use function Amp\async;
use Monolog\Logger;

class UserController {
  /**
   * @var \App\Models\IDbAdapter
   */
  protected $db;
  protected $log;

  /**
   * @param IDbAdapter $dbAdapter
   */
  public function __construct(IDbAdapter $dbAdapter, Logger $log) {
    $this->db = $dbAdapter;
    $this->log = $log;
  }

  /**
   * Fetch all users
   * 
   * @return array
   */
  public function fetchAll() : array {
    $sql = 'SELECT * FROM users';
    $userRecords = $this->db->fetchAll($sql);
    $this->log->info('Fetch all posts');

    $users = array();

    if(count($userRecords) > 0) {
      foreach($userRecords as $userRecord) {
        $user = array(
          'id' => $userRecord['id'], 
          'first_name' => $userRecord['first_name'],
          'last_name' => $userRecord['last_name'],
          'username' => $userRecord['username'],
          'email' => $userRecord['email']
        );

        $users[] = $user;
      }
    }

    return $users;
  }

  /**
   * Fetch a users by id
   * 
   * @param int $id
   * 
   * @return array
   */
  public function fetchById(int $id) : array {
    $sql = 'SELECT * FROM users WHERE id =?';
    $userRecord = $this->db->fetchOne($sql, [$id]);
    $this->log->info(`Fetch By Id ?`, [$id]);

    if($userRecord) {
      return array(
        'id' => $userRecord['id'], 
        'first_name' => $userRecord['first_name'],
        'last_name' => $userRecord['last_name'],
        'username' => $userRecord['username'],
        'email' => $userRecord['email']
      );
    }

    return null;
  }

  /**
   * Insert a user record
   * 
   * @param User $user
   * 
   * @return User
   */
  public function insert(User $user) : array {
    $userRecord = [
      'first_name' => $user->getFirstName(),
      'last_name' => $user->getLastName(),
      'username' => $user->getUsername(),
      'password' => $user->getPassword(),
      'email' => $user->getEmail()
    ];

    $id = $this->db->insert('users', $userRecord);
    $this->log->info('Insert new user');

    return array(
      'id' => $id, 
      'first_name' => $user->getFirstName(),
      'last_name' => $user->getLastName(),
      'username' => $user->getUsername(),
      'email' => $user->getEmail()
    );
  }

    /**
   * Update a user
   * 
   * @param User $user
   * 
   * @return array
   */
  public function update(User $user) : array {
    $userRecord = [
      'first_name' => $user->getFirstName(),
      'last_name' => $user->getLastName(),
      'username' => $user->getUsername(),
      'password' => $user->getPassword(),
      'email' => $user->getEmail()
    ];

    $this->db->update('users', $userRecord, ['id' => $user->getId()]);
    $this->log->info('Update record Id', ['id' => $user->getId()]);

    return array(
      'id' => $user->getId(), 
      'first_name' => $user->getFirstName(),
      'last_name' => $user->getLastName(),
      'username' => $user->getUsername(),
      'email' => $user->getEmail()
    );
  }

  /**
   * Delete a user
   * 
   * @param User $user
   */
  public function delete(int $id) : void {
    $this->db->delete('users', ['id' => $id]);
    $this->log->info('Delete record Id ', ['id' => $id]);
  }

  /**
   * Register new user
   * 
   * @param User $user
   * 
   * @return array
   */
  public function registerUser(User $user) : array {
    $user_data = $this->insert($user);
    $emailer = new Emailer($this->log);
    
    $future = async(static function () use ($emailer) {
      $emailer->send();
    });

    $future->await();
    $this->log->info('Registration complete');

    return $user_data;
  }
}
