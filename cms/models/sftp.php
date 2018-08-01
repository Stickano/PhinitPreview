<?php

class Sftp {

    private $host;
    private $port;
    private $session;
    private $sftp;

    private $keys;
    private $privateKey;
    private $publicKey;

    public function __construct(string $host, int $port=22) {
        $this->host = $host;
        $this->port = $port;
        $this->keys = false;
    }

    /**
     * Sets, and activates, the path to public and private SSH key
     * @param string $public  Path to public-key (~/.ssh/id_rsa.pub)
     * @param string $private Path to private-key (~/.ssh/id_rsa)
     */
    public function keys(string $public, string $private) {
        if(is_file($private) && is_file($public)) {
            $this->privateKey = $private;
            $this->publicKey  = $public;
            $this->keys       = true;
        }
    }

    /**
     * Connect to a server
     * @param string      $username The username to connect with
     * @param string|null $password The password to use. Can be left
     *                              blank, ie. if using keys
     */
    public function connect(string $username, string $password=null) {

        if (!$this->session = ssh2_connect($this->host, $this->port))
            throw new Exception("Cannot connect to server");

        # Password authentication
        if (!$this->keys && !ssh2_auth_password($this->session, $username, $password))
            throw new Exception("Invalid username or password");


        # Priv/pub key authentication
        if ($this->keys && !ssh2_auth_pubkey_file($this->session,
                                                  $username,
                                                  $this->publicKey,
                                                  $this->privateKey,
                                                  $password ))
            throw new Exception("Authentication rejected by server");

        $this->sftp = ssh2_sftp($this->session);
    }

    /**
     * Scans a given directory on the connected host
     * @param string $dir The directory to scan
     */
    public function scanDir(string $dir){
        if(isset($this->sftp))
            return scandir('ssh2.sftp://'.(int)$this->sftp . $dir);
    }

    /**
     * Send a file to the host
     * @param string      $src   Source destination (path/local)
     * @param string      $dst   Remote destination (path/remote)
     */
    public function sendFile(string $src, string $dst) {
        $chmod = "0"+$chmod;
        if(isset($this->sftp)){
            $stream = @fopen("ssh2.sftp://".$this->session . $dst, 'w');
            $data   = @file_get_contents($src);

            if (!$stream)
                throw new Exception("Could not open remote file: ".$dst);

            if ($data === false)
                throw new Exception("Could not open local file: ".$src);

            if (@fwrite($stream, $data) === false)
                throw new Exception("Could not send data from file: ".$src);

            @fclose($stream);
        }
    }

    /**
     * Request a file from the host
     * There might be a bug with ssh2_scp_recv() and libssh2 - I haven't tested. TODO
     * I had an issue with ssh2_scp_send() at least, so I'd say there is a pretty
     * good chance that this won't work either.
     * @param string $src Source destination (path/remote)
     * @param string $dst Local destination (path/local)
     */
    public function receiveFile(string $src, string $dst) {
        if(isset($this->sftp))
            return ssh2_scp_recv($this->session, $src, $dst);
    }

    /**
     * Open a file on the host
     * @param string $dst The destination of the file (remote)
     */
    public function openFile(string $dst) {
        if(isset($this->sftp))
            return fopen("ssh2.sftp://".(int)$sftp . $dst, 'r');
    }

    /**
     * Deletes a file from the host
     * @param string $dst The destination file (path/remote)
     */
    public function removeFile(string $dst) {
        if ($this->sftp)
            return ssh2_sftp_unlink($this->sftp, $dst);
    }

    /**
     * Creates a new folder on host
     * @param string $dst Destination for the foler (path/remote)
     */
    public function createFolder(string $dst) {
        if ($this->sftp)
            return ssh2_sftp_mkdir($this->sftp, $dst);
    }

    /**
     * Deletes an EMPTY folder
     * @param string $dst The folder to remove (Needs to be empty)
     */
    public function removeFolder(string $dst) {
        if ($this->sftp)
            return ssh2_sftp_rmdir($this->sftp, $dst);
    }

    /**
     * Execute a command on the remote server
     * @param string $command The Unix command to execute
     */
    public function execute(string $command) {
        if ($this->stfp)
            return ssh2_exec($this->sftp, $command);
    }

}

?>