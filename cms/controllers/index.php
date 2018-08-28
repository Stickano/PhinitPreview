<?php

class IndexController{

    private $conn;
    private $db;
    private $sessions;

    # DB values
    private $contents;

    public function __construct(Connection $conn, Crud $db, Session $sessions){
        $this->conn     = $conn;
        $this->db       = $db;
        $this->sessions = $sessions;

        # Fetch db values
        $select         = ['*' => 'examples'];
        $this->contents = $this->db->read($select);
        if (!$this->contents)
            $this->contents = array(); # Empty array in case db is empty
    }

    /**
     * Returns all the examples
     * @return array   Examples, fetched from the db
     */
    public function getExamples(){
        return $this->contents;
    }
}

?>
