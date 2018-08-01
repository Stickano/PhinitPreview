<?php

class sshConf {

    # Location for private & public ssh keys.
    # Provide the full path for the keys.
    private $privateKey = "";
    private $publicKey  = "";

    # Server IP address
    private $server     = "";

    public function get(){
        return array('priv' => $this->privateKey,
                     'pub'  => $this->publicKey,
                     'ip'   => $this->server);
    }
}

?>