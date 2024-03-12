<?php

namespace App\Models;

use Monolog\Logger;

class Emailer {
    /**
     * @var Logger
     */
    private $log;

    /**
     * Constrcutor
     * 
     * @param Logger $log
     */
    public function __construct(Logger $log) {
        $this->log = $log;
    }

    /**
     * Method to simulate sending an email
     * 
     */
    public function send() : void {
        $this->log->info("Email sent");
    }
}
