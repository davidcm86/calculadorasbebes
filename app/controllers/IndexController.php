<?php
use Phalcon\Mvc\Dispatcher;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->assets->addJs('js/common.js');
        //$lang   = $this->dispatcher->getParam('language');
        //print_r('lang:' . $lang);die;
        $this->view->title = "Inicio calculadoras";
        $this->view->t = $this->getTranslation();
    }

}

