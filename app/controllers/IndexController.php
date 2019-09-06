<?php
use Phalcon\Mvc\Dispatcher;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $t = $this->_getTranslation();
        $lang = $this->dispatcher->getParam('language');
        $manager = $this->modelsManager;
        $query = $manager->createQuery(
            'SELECT IdiomasCalculadoras.slug AS slug, IdiomasCalculadoras.nombre AS nombre_calculadora, Calculadoras.img_ruta AS 
            ruta_imagen FROM Calculadoras JOIN IdiomasCalculadoras WHERE IdiomasCalculadoras.idioma_id = :lang: ORDER BY rand()'
        );
        $query->cache(
            [
                'key'      => 'calculadoras-index-' . $lang,
                'lifetime' => 3600
            ]
        );
        $calculadoras = $query->execute([
            'lang' => $lang,
        ]);
        $this->view->calculadoras = $calculadoras;
        $this->view->titlePagina = 'principal-meta-title';
        $this->view->descriptionMeta = 'principal-meta-description';
        // metas jsonld
        $doc = (object)array(
            "@context" => "http://schema.org",
            "@type" => "WebPage",
            "name" => $t->_('principal-meta-title'),
            "description" => $t->_('principal-meta-description'),
        );
        $this->view->jsonld = json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

}

