<?php

class IndexController{

    private $conn;
    private $db;
    private $sessions;

    # String Handler
    public  $sh;
    private $examples;
    private $welcome;

    # Let's make it easy for them snoopy eyes
    private $nsa;
    private $time;

    public function __construct(Connection $conn, Crud $db, Session $sessions){
        $this->conn     = $conn;
        $this->db       = $db;
        $this->sessions = $sessions;
        $this->examples = array();
        $this->welcome  = array();

        # Fetch all db values (examples)
        $select = ['*'        => 'examples'];
        $order  = ['headline' => 'asc'];
        if ($this->db->read($select))
            $this->examples = $this->db->read($select, null, $order);

        # Fetch the welcome message
        $select = ['message' => 'announcements'];
        $order  = ['id'      => 'desc'];
        $limit  = 1;
        if ($this->db->read($select))
            $this->welcome = $this->db->read($select, null, $order, $limit);

        # Handle models
        self::fetchModels();

        $this->sh   = new StringHandler();
        $this->nsa  = new Client();
        $this->time = new Time();

        self::collectClientData();
        self::formatExamples();
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
     * @return array              The associated examples
     */
    public function getExamples(){
        return $this->examples;
    }

    /**
     * Return the welcome message to the view.
     * @return array The welcome message.
     */
    public function getAnnouncement() {
        if (!empty($this->welcome))
            return nl2br($this->welcome[0]['message']);
        return;
    }

    /**
     * This will replace spaces with a minus (-)
     * This is used for the IDs for each example.
     * @param  string $string The headline (string)
     * @return string         The formatted headline
     */
    public function replaceSpaces(string $string){
        return preg_replace('/\s+/', '-', $string);
    }

    /**
     * This will replace the ''' keyword with code box element
     * The PHP in the examples are evaluated in the view..
     * Unfortunately the only way of doing it as I see it this day
     * @return          Formats and re-sets the $examples array
     */
    private function formatExamples(){
        if(empty($this->examples))
            return;

        foreach ($this->examples as $key => $value) {
            $txt                             = $value['content'];
            $replaceWith                     = ['<mark>', '</mark>'];
            $txt                             = $this->sh->multiSearch($value['content'], "`", $replaceWith);
            $this->examples[$key]['content'] = $txt;
        }

        foreach ($this->examples as $key => $value) {
            $txt                             = $value['content'];
            $replaceWith                     = ['<pre class="PHP"><code>', '</code></pre>'];
            $txt                             = $this->sh->multiSearch($value['content'], "'''", $replaceWith);
            $this->examples[$key]['content'] = $txt;
        }

    }

    /**
     * The logic is a little ..idk, chaotic I guess.
     * So if an example holds more """ keywords in the example,
     * code to be evaluated, then it will break that example down
     * to each of those snippets.So from 0 to end of first """ block
     * and so forth.This makes each example loopable in the view,
     * which seems to make it a little easier from there at least.
     * @param  string $example   The example (from the db)
     * @param  array  $positions The positions of """ blocks
     * @return array            Broken down examples, by """ blocks
     */
    public function splitExample(string $example, array $positions) {
        $subExamples = array();
        $start = 0;
        foreach ($positions as $key) {
            $end           = $key[1]-$start;
            $subExamples[] = substr($example, $start, $end+3);
            $start         = $key[1]+3;
        }
        #echo'<pre>'.print_r($subExamples).'</pre>';die;
        # Get the last of content, if any after the last """ keyword
        $lastPos = $positions[count($positions)-1][1];
        if (strlen($example) > $lastPos){
            $subExamples[] = substr($example, $lastPos+3);
        }

        return $subExamples;
    }

    /**
     * We will try to catch some user information.
     * Data is valuable they say.
     */
    private function collectClientData() {
        $nsa     = $this->nsa;
        $time    = $this->time->timestamp();
        $device  = $nsa->browscap()['device'];
        $system  = $nsa->browscap()['system'];
        $browser = $nsa->browscap()['browser'];
        $lang    = $nsa->lang();
        $prev    = $nsa->getUrl(true);
        $ip      = $nsa->ip();

        $table = "nsa";
        $data  = ['timestamp'        => $time,
                  'device'           => $device,
                  'system'           => $system,
                  'browser'          => $browser,
                  'browser_language' => $lang,
                  'previous_page'    => $prev,
                  'ip_address'       => $ip];
        $this->db->create($table, $data);
    }
}

?>
