<?php
use Phalcon\Mvc\Dispatcher;

class IndexController extends ControllerBase
{

    public function initialize()
    {
        $this->view->setTemplateAfter('common');
    }

    public function indexAction()
    {
        //$lang   = $this->dispatcher->getParam('language');
        //print_r('lang:' . $lang);die;
        $this->view->lang = "es";
        $this->view->title = "Inicio calculadoras";
        $this->view->t = $this->getTranslation();
    }

}

