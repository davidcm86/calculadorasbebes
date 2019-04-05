<?php

class ErroresController extends \Phalcon\Mvc\Controller
{

    public function notFoundAction()
    {
        $response = new \Phalcon\Http\Response();
        $response->setStatusCode(404, "Not Found");
        $this->response->send();
    }

    public function internalErrorAction()
    {
        $response = new \Phalcon\Http\Response();
        $response->setStatusCode(500, "File not Found");
        $this->response->send();
    }

}