<?php

namespace core\base\exсeptions;

use com_exception;
use \core\base\controller\BaseMethods;

class RouteExсeption extends \Exception
{
use BaseMethods;

protected $messages;

public function __construct($message = '', $code = 0)
{
    parent::__construct($message, $code);

    $this->messages = include "messages.php";

    $error = $this->getMessage() ? $this->getMessage() :  $this->messages[$this->getCode()];
    $error .= "\r\n" . 'file ' . $this->getFile() . "\r\n" . 'at line ' . $this->getLine() . "\r\n";
    
    if($this->messages[$this->getCode()]) $this->message = $this->messages[$this->getCode()];

    $this->writeLog($error);
}
}
