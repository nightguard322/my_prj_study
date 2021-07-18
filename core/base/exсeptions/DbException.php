<?php

namespace core\base\exсeptions;

use \core\base\controller\BaseMethods;

class DbException extends \Exception
        
{

    protected $messages;

    use BaseMethods;

    public function __construct($message = '', $code = '0'){

        parent::__construct($message, $code);

        $this->messages = include 'messages.php';

        $error = $this->getMessage() ? $this->getMessage() : $this->messages[$this->getCode()];
        $error .= "\r\n" .'file ' . $this->getFile() . "\r\n" . 'at line ' . $this->getLine();
        
        // if($this->messages[$this->getCode()]) $this->message = $this->messages[$this->getCode()];

        $this->writeLog($error, 'db_log.txt');
    }
}
