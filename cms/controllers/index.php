<?php

require_once 'models/sftp.php';

class IndexController{

    private $conn;
    private $db;
    private $sessions;

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

        $this->ftp = new Sftp('167.99.37.78');
        $this->ftp->Keys('/home/stick/.ssh/id_rsa.pub','/home/stick/.ssh/id_rsa');

        # Fetch db values
        $select                    = ['*' => 'examples'];
        $this->contents            = $this->db->read($select);
        if (!$this->contents)
            $this->contents = array(); # Empty array in case db is empty

        # Set view values
        self::setViewInputs();
        self::checkErrors();
    }

    /**
     * Upon failed attempts, the inputs are saved and here.
     * When editing an example, the values from the db will
     * also be fetched here.
     * They'll be set for the view to collect
     * @return     Sets $viewInputValues
     */
    private function setViewInputs(){
        if($this->sessions->isset('category')){
            $this->viewInputValues = [  'headline'  => $this->sessions->get('headline'),
                                        'content'   => $this->sessions->get('content')];

            $this->sessions->unset('headline');
            $this->sessions->unset('content');
        }

        if (isset($_GET['edit']) && is_numeric($_GET['edit'])){
            $id = $_GET['edit'];
            if(!empty($this->contents[$id])){
                $this->viewInputValues = [  'headline'  => $this->contents[$id]['headline'],
                                            'content'   => $this->contents[$id]['content']];
            }
        }
    }

    /**
     * Return any input values to the view.
     * @return array Input values
     */
    public function getViewInputs(){
        return $this->viewInputValues;
    }

    /**
     * Adds a new example to the database
     */
    public function addExample(){
        $headline    = $_POST['headline'];
        $content     = $_POST['content'];

        # Create sessions so we don't have to retype incase of error
        $this->sessions->set('headline',$headline);
        $this->sessions->set('content',$content);

        # Check for empty values (requirements)
        if(empty($headline))
            $this->sessions->set('error','Missing headline');
        if(empty($content))
            $this->sessions->set('error','Missing content');

        # Insert example to db
        if(!$this->sessions->isset('error')){

            # Transfer model to server
            $file = $_FILES['file'];
            $this->ftp->Connect('stick');
            $this->ftp->SendFile($file, '/var/www/html/models/');

            # Insert the preview
            $values = ['headline'   => $headline,
                       'content'    => $content];

            try {
                if($this->db->create('examples', $values)){
                    $this->sessions->unset('headline');
                    $this->sessions->unset('content');
                }
            } catch (Exception $e) {
                $this->sessions->set('error', $e);
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

        $table = "examples";
        $data  = ['headline' => $_POST['headline'],
                  'content'  => $_POST['content']];
        $where = ['id' => $id];
        try {
            if ($this->db->update($table, $data, $where))
                $this->sessions->set('message', 'Example updated');
        } catch (Exception $e) {
            $this->sessions->set('error', $e);
        }
        header("location:index.php");
    }

    /**
     * Returns all the examples
     * @return array   Examples, fetched from the db
     */
    public function getExamples(){
        return $this->contents;
    }

    /**
     * Checks, sets and unsets errors (from session to variable)
     * @return      Sets $error
     */
    private function checkErrors(){
        if($this->sessions->isset('error')){
            $this->error = $this->sessions->get('error');
            $this->sessions->unset('error');
        }
    }

    /**
     * Returns the error (if any) to the view
     * @return string Error
     */
    public function getError(){
        return $this->error;
    }

    /**
     * Fetches all the files (modules) from the PhinitPreview server
     */
    public function GetModules() {
        $this->ftp->Connect('stick');
        return $this->ftp->ScanDir('/var/www/html/models/');
    }
}

?>
