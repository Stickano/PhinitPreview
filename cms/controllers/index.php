<?php

require_once 'models/upload.php';

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
    private $upload;

    public function __construct(Connection $conn, Crud $db, Session $sessions){
        $this->conn     = $conn;
        $this->db       = $db;
        $this->sessions = $sessions;

        # Fetch db values
        $select                    = ['*' => 'examples'];
        $this->contents            = $this->db->read($select);
        if (!$this->contents)
            $this->contents = array(); # Empty array in case db is empty

        # Set view values
        self::failedAttempt();
        self::checkErrors();
    }

    /**
     * Upon failed attempts, the inputs are saved and here
     * they are set for the view to collect
     * @return     Sets $viewInputValues
     */
    private function failedAttempt(){
        if($this->sessions->isset('category')){
            $this->viewInputValues = [  'headline'  => $this->sessions->get('headline'),
                                        'content'   => $this->sessions->get('content')];

            $this->sessions->unset('headline');
            $this->sessions->unset('content');
        }
    }

    /**
     * Here the input values for the view, from failed attempts,
     * are returned to the view
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

            # Handle file upload (PHP model)
            # TODO: ftp upload
            $input = 'file';
            $filetypes = ['php'];
            $this->upload = new Upload($input, $filetypes);
            #$this->upload->noReturn();
            $this->upload->overwrite();
            $this->upload->upload('../client/models/');

            # Insert the preview
            $values = ['headline'   => $headline,
                       'content'    => $content];

            if($this->db->create('examples', $values)){
                $this->sessions->unset('headline');
                $this->sessions->unset('content');
            }
        }

        header("location:".$_SERVER['PHP_SELF']);
    }

    /**
     * Returns all the examples
     * @param  int|null $categoryId All examples within this category
     * @return array                All the assosiated examples
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
}

?>
