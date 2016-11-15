<?php
class RespObj
{
    public $username;
    public $response;
    public function __construct($username, $response)
    {

        $this->username = $username;
        $this->response = $response;

    }
}
