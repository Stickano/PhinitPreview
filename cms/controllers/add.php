<?php

require_once 'models/sftp.php';
require_once 'resources/sshConf.php';

class AddController {

    private $conn;
    private $db;
    private $sessions;
    private $sshConf;

    # DB values
    private $contents;

    # Values that will be fetched from the view
    private $viewInputValues;
    private $error;

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

        self::setViewInputs();
    }

    /**
     * Upon failed attempts, the inputs are saved and here.
     * When editing an example, the values from the db will
     * also be fetched here.
     * They'll be set for the view to collect
     * @return     Sets $viewInputValues
     */
    private function setViewInputs(){

        $this->viewInputValues['headline'] = "";
        $this->viewInputValues['content']  = "";

        if($this->sessions->isset('headline')) {
            $this->viewInputValues['headline'] = $this->sessions->get('headline');
            $this->sessions->unset('headline');
        }

        if ($this->sessions->isset('content')) {
            $this->viewInputValues['content'] = $this->sessions->get('content');
            $this->sessions->unset('content');
        }

        # If editing an example, set the inputs from the datbase
        if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
            $select = ['*' => 'examples'];
            $where  = ['id' => $_GET['edit']];
            if ($this->db->read($select, $where)) {
                $result = $this->db->read($select, $where);
                $this->viewInputValues = ['headline'  => $result[$_GET['edit']]['headline'],
                                          'content'   => $result[$_GET['edit']]['content']];
            }
        }
    }

    /**
     * Returns the view-inputs,
     * from failed attempts and when editing,
     * to the view.
     * @return array [Headline => Content]
     */
    public function getViewInputs() {
        return $this->viewInputValues;
    }

    /**
     * Adds a new example to the database
     */
    public function addExample(){
        $headline    = $_POST['headline'];
        $content     = $_POST['content'];

        # Check for empty values (requirements)
        if(empty($headline))
            $this->sessions->set('error','Missing headline');
        if(empty($content))
            $this->sessions->set('error','Missing content');

        # Store the values in case of error
        if (!empty($content))
            $this->sessions->set('content',$content);
        if (!empty($headline))
            $this->sessions->set('headline',$headline);

        # Insert example to db
        if(!$this->sessions->isset('error')){

            # Transfer model to server
            try {
                $file = $_FILES['file'];
                $this->ftp->connect('stick');
                $this->ftp->sendFile($file['tmp_name'], '/var/www/html/models/'.$file['name']);
            }catch(Exception $e){
                $this->sessions->set('error', $e.getMessage());
            }

            # Insert the preview
            try {
                $values = ['headline'      => $headline,
                           'content'       => $content,
                           'file_location' => '/var/www/html/models/'.$file['name']];

                if($this->db->create('examples', $values)){
                    $this->sessions->unset('headline');
                    $this->sessions->unset('content');
                }
            } catch (Exception $e) {
                $this->sessions->set('error', $e.getMessage());
            }
        }

        header("location:index.php");
    }

    /**
     * Updates an example in the database
     * @param  int    $id The id of which example to update
     * @return        Redirects back to CMS page
     */
    public function editExample(int $id){
        if(empty($this->contents[$id])){
            header("location:index.php");
            exit;
        }

        if(empty($_POST['headline']) || empty($_POST['content'])){
            $this->sessions->set('error', 'Headline and Content both needs values.');
            header("location:index.php");
            exit;
        }

        # Update content (db)
        try {
            $table = "examples";
            $data  = ['headline' => $_POST['headline'],
                      'content'  => $_POST['content']];
            $where = ['id' => $id];

            if ($this->db->update($table, $data, $where))
                $this->sessions->set('message', 'Example updated');

        } catch (Exception $e) {
            $this->sessions->set('error', $e.getMessage());
        }

        # Update file (if any)
        if ($_FILES['file']['size'] != 0) {
            try {
                $file = $_FILES['file'];
                $this->ftp->connect('stick');
                $this->ftp->sendFile($file['tmp_name'], '/var/www/html/models/'.$file['name']);
            }catch(Exception $e){
                $this->sessions->set('error', $e.getMessage());
            }
        }

        header("location:index.php");
    }
}

?>
