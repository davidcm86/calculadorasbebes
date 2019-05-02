<?php
use Phalcon\Mvc\Dispatcher;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $lang = $this->dispatcher->getParam('language');
        $phql = 'SELECT IdiomasCalculadoras.slug AS slug, IdiomasCalculadoras.nombre AS nombre_calculadora FROM Calculadoras JOIN IdiomasCalculadoras  WHERE IdiomasCalculadoras.idioma_id = "'.$lang.'"';
        $manager = $this->modelsManager;
        $this->view->calculadoras = $manager->executeQuery($phql);
        $this->view->titlePagina = 'principal-meta-title';
        $this->view->descriptionMeta = 'principal-meta-description';
    }

}

