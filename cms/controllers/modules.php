<?php

require_once 'models/sftp.php';
require_once 'resources/sshConf.php';

class ModulesController{

    private $conn;
    private $db;
    private $sessions;
    private $sshConf;

    # Various models
    private $ftp;

    public function __construct(Connection $conn, Crud $db, Session $sessions){
        $this->conn     = $conn;
        $this->db       = $db;
        $this->sessions = $sessions;

        # SSH keys and server IP
        $this->sshConf  = new sshConf();
        $this->ftp      = new Sftp($this->sshConf->get()['ip']);
        $this->ftp->Keys($this->sshConf->get()['pub'],
                         $this->sshConf->get()['priv']);

    }

    /**
     * Fetches all the files (modules) from the PhinitPreview server
     */
    public function getModules() {
        try{
            $this->ftp->connect('stick');
            return $this->ftp->scanDir('/var/www/html/models/');
        }catch(Exception $e){
            $this->sessions->set('error',$e->getMessage());
        }
    }

    /**
     * Deletes one of the uploaded classes
     * @return     Redirects back
     */
    public function deleteFile(){
        try{
            $this->ftp->connect('stick');
            if($this->ftp->removeFile('/var/www/html/models/'.$_POST['file']))
                $this->sessions->set('message', 'Removed '.$_POST['file']);
        }catch(Exception $e){
            $this->sessions->set('error',$e->getMessage());
        }

        header("location:index.php?modules");
    }
}

?>
