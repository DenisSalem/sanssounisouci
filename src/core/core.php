<?php
  if ( ! defined("SSNS_SCRIPT_INCLUSION") ) die('NO F*CKING DIRECT SCRIPT ACCESS ALLOWED LOL');

  class LanguageHandler {
    private $lang;

    function __construct() {
      $http_accept_language_array = explode(",",$_SERVER["HTTP_ACCEPT_LANGUAGE"]);
      if ("fr" == $http_accept_language_array[0]) {
        $this->lang = "fr";
      }
      else {
        $this->lang = "fr";
      }
    }
    
    private function GetLine($id) {
      $messages = fopen("Messages/".$this->lang,'r');
      $i = 0;
      while (($line = fgets($messages)) !== false) {
        if($i == $id) {
          return $line;
        }
        $i++;
      }
    }

    public function GetMessageById($messageId) {
      return $this->GetLine($messageId);
    }

    public function EchoMessageById($messageId) {
      echo $this->GetLine($messageId);
    }
  }

  function __ERROR_HANDLER__($errno, $errstr, $errfile, $errline, $errcontext) {
    if (0 === error_reporting()) {
      return false;
    }	

    throw new ErrorException($errstr, $errno, $errno, $errfile, $errline);
  };

  function EchoErrors($errors) {
    foreach($errors as $value) {
      echo "<p class=\"error\">".$value."</p>\n";
    }
  }

  //Prevent undefined error
  function FetchFromArray($array, $index) {
    if (isset($array[$index])) {
      return $array[$index];
    }
    else {
      return NULL;
    }
  }

  function FetchSentData($index, $allowEmpty=true) {
    if(isset($_POST[$index])) {
      if(!$allowEmpty && empty($_POST[$index])) {
        Throw new ErrorException("Empty data", 255);
      }
      return $_POST[$index];
    }
    if(isset($_GET[$index])) {
      if(!$allowEmpty && empty($_GET[$index])) {
        Throw new ErrorException("Empty data", 255);
      }
      return $_GET[$index];
    }
    Throw new ErrorException("Undefined Index", 255);
  }

  function isLoggedIn() {
    if (isset($_SESSION["userid"])) {
      return true;
    }
    return false;
  }

  function login() {
    global $dbDriver;
    global $TABLE_PREFIX;
    $QueryResult = $dbDriver->PrepareAndExecute(
      "SELECT * FROM ".$TABLE_PREFIX."users WHERE nick=$1 AND password=$2",
      array(
        $_POST["nick"],
        hash('sha512',$_POST["password"])
      )
    );
    if ($QueryResult->CountRow() == 1) {
      $_SESSION["userid"] = $QueryResult->get()->id;
      return true;
    }
    return false;

  }

  function getUser($id) {
    global $dbDriver;
    global $TABLE_PREFIX;
    $QueryResult = $dbDriver->PrepareAndExecute(
      "SELECT * FROM ".$TABLE_PREFIX."users WHERE id=$1",
      array(
        $id
      )
    );
    if ($QueryResult->CountRow() == 1) {
      return $QueryResult->get();
    }
    return NULL;
  }

  require_once("databaseDrivers.php");
?>
