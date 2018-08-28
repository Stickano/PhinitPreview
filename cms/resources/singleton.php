<?php

@session_start();
require_once('models/connection.php');
require_once('models/crud.php');
require_once('resources/session.php');
require_once('resources/credentials.php');

final class Singleton {

    private static $instance;

    # database connection
    private static $conn;
    private static $crud;

    # View and Controller
    public static $page;
    public static $controller;

    # Session Handler & Errors
    public static $session;
    public static $error;

    # Private constructor to ensure it won't be initialized
    private function __construct(){}

    /**
     * This is the initializer for this object
     * It will initialize a Connection and CRUD class
     * It will determine which View to load and its apropriate Controller
     * It will store the page you hit (nowPage), along with storing the previous page (prePage)
     * And it will initialize a couple of typical classes, like meta and time (used in footer)
     * @return object This (only) instance
     */
    public static function init(){
        if(!isset(self::$instance)){

            self::$instance = new self();
            self::$session = Session::init();

            self::setConn();
            self::setDb();
            self::getView();
            self::getController();
            self::checkError();
        }

        return self::$instance;
    }

    /**
     * This will determine which page(view) to load
     */
    private static function getView(){

        $pages = array();
        foreach (glob("views/*.php") as $file) {
            $page = substr($file, 6, -4);
            $pages[] = $page;
        }

        $search = array_intersect($pages, array_keys($_GET));
        if(in_array('index', $pages)){
            $pos = array_keys($pages, 'index')[0];
            self::$page = $pages[$pos];
        }

        if(!empty($search)){
            $search = array_values($search);
            self::$page = $search[0];
        }
    }

    /**
     * This will load the appropriate controller
     */
    public static function getController(){
        require_once('controllers/'.self::$page.'.php');
        $controller = ucfirst(self::$page).'Controller';
        self::$controller = new $controller(self::getConn(), self::getDb(), self::$session);
    }

    /**
     * Creates a connection object
     */
    private static function setConn(){
        $credentials = new Credentials();
        $val = $credentials->get();
        self::$conn = new Connection($val['host'], $val['user'], $val['pass'], $val['db']);
    }

    /**
     * Creates a CRUD object
     */
    private static function setDb(){
        self::$crud = new Crud(self::getConn());
    }

    /**
     * Returns the connection
     * @return object Connection class
     */
    public static function getConn(){
        return self::$conn;
    }

    /**
     * Return the CRUD methods
     * @return object Class holding the CRUD methods
     */
    public static function getDb(){
        return self::$crud;
    }

    /**
     * Checks if any errors, sets $error and unsets session
     * @return          Defines the $error variable
     */
    # TODO: Will change to several errors handling
    private static function checkError() {
        if(isset($_SESSION['error'])) {
            self::$error =  $_SESSION['error'];
            unset($_SESSION['error']);
        }
    }

    /**
     * Returns any error message
     * @return string   Error message TODO: Will change
     */
    public static function getError() {
        return self::$error;
    }

    /**
     * Will generate '&nbsp;'
     * @param  int    $count The amount of spaces
     * @return string        The spaces
     */
    public function spaces(int $count){
        $spaces = '';
        for($i=0; $i<$count; $i++){
            $spaces .= '&nbsp;';
        }
        return $spaces;
    }
}

?>
