<?php

class IndexController{

    private $conn;
    private $db;
    private $sessions;

    # String Handler
    public $sh;
    private $examples;

    public function __construct(Connection $conn, Crud $db, Session $sessions){
        $this->conn     = $conn;
        $this->db       = $db;
        $this->sessions = $sessions;

        $this->examples       = array();

        # Fetch all db values (examples)
        $select = ['*' => 'examples'];
        $this->examples = $this->db->read($select);

        # Handle models
        self::fetchModels();
        $this->sh = new StringHandler();

        self::formatExamples();
    }

    public function test(){
        return $this->examples;
    }

    /**
     * Require all the models
     * @return      Fetches every document (model) in /models
     */
    private function fetchModels(){
        foreach (glob('models/*.php') as $file) {
            require_once $file;
        }
    }

    /**
     * Returns all the examples for a given category
     * @param  int    $categoryId The ID of the category
     * @return array              The assosiated examples
     */
    public function getExamples(){
        return $this->examples;
    }

    private function formatExamples(){
        if(!empty($this->examples)){
            foreach ($this->examples as $key => $value) {
                $txt = $value['content'];
                $replaceWith = ['<pre><code>', '</code></pre>'];
                $txt = $this->sh->multiSearch($value['content'], "'''", $replaceWith);
                $this->examples[$key]['content'] = $txt;
            }
        }
    }
}

?>
