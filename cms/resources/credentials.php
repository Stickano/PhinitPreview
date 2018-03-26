<?php

class Credentials {
    private $host = '';
    private $user = '';
    private $pass = '';
    private $db   = '';

    public function get(){
        return array('host' => $this->host,
                    'user' => $this->user,
                    'pass' => $this->pass,
                    'db' => $this->db);
    }
}

?>