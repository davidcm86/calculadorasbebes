<?php

use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Http\Request\Exception;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Response;

class ControllerBase extends Controller
{
    public function initialize()
    {
        $this->view->setTemplateAfter('common');
        $this->view->dominioPhp = DOMINIO; // así podemos pintar la variable en volt, con los tag de php no lo coge
        $this->view->lang = $this->dispatcher->getParam('language');
        $this->view->languages = ['en' => 'English', 'es' => 'Español'];
        $this->tag->setDefault('select-language', $this->view->lang);
        if (empty($this->dispatcher->getParam('language'))) {
            $this->view->language = $this->request->getBestLanguage();
        } else {
            $this->view->language = $this->__getLanguageFromParam($this->dispatcher->getParam('language'));
        }
    }

    public function beforeExecuteRoute(){
        $lang = $this->dispatcher->getParam('language');
        if (empty($lang)) {
            $idiomaExplode = explode('-', $this->request->getBestLanguage());
            if (!empty($idiomaExplode[0]) && $this->__existLanguage($idiomaExplode[0])) {
                $this->response->redirect('/' . $idiomaExplode[0]);
            } else {
                $this->response->redirect('/es');
            }
        } else {
            if (!$this->__existLanguage($lang)) {
                // 404 si no existe el idioma
                $response = new \Phalcon\Http\Response();
                $response->setStatusCode(404, "Not Found");
                $this->response->send();
                $this->dispatcher->forward([
                    'controller' => 'errores',
                    'action' => 'notFound'
                ]);
            }
        }
    }

    protected function getTranslation()
    {
        // Ask browser what is the best language
        $lang = $this->dispatcher->getParam('language');
        if (!empty($lang) && $this->__existLanguage($lang)) {
            $idiomaSiglas = $lang;
        } else {
            $language = $this->request->getBestLanguage();
            $idiomaExplode = explode('-', $language);
            $messages = [];
            switch ($idiomaExplode[0]) {
                case 'es':
                    $idiomaSiglas = 'es';
                    break;
                case 'en':
                    $idiomaSiglas = 'en';
                    break;
                default;
                    $idiomaSiglas = 'es';
            }
        }
        $translationFile = APP_PATH . '/messages/' . $idiomaSiglas . '.php';
        // Check if we have a translation file for that lang
        if (file_exists($translationFile)) {
            require $translationFile;
        } else {
            // Fallback to some default
            require APP_PATH . '/messages/es.php';
        }
        // Return a translation object $messages comes from the require
        // statement above
        return new NativeArray(
            [
                'content' => $messages,
            ]
        );
    }

    private function __existLanguage($language) {
        if (in_array($language, ['es', 'en'])) return true;
        return false;
    }

    private function __getLanguageFromParam($language) {
        switch ($language) {
            case 'es':
                $languageReturn = 'es-ES';
                break;
            case 'en':
                $languageReturn = 'en-EN';
                break;
            default:
                $languageReturn = 'es-ES';
        }
        return $languageReturn;
    }

}
