<?php

class ValidationException extends Exception
{
    protected $errorCode;

    public function __construct($message, $errorCode)
    {
        parent::__construct($message);
        $this->errorCode = $errorCode;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
