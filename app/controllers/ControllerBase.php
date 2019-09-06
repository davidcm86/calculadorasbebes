<?php

use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Http\Request\Exception;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Response;
use Phalcon\Http\Request;


class ControllerBase extends Controller
{
    public function initialize()
    {
        $this->assets->addJs('js/common.js');
        $this->view->t = $this->_getTranslation();
        // Get the Http-X-Requested-With header
        /*$requestedWith = $this->request->getHeader('HTTP_X_REQUESTED_WITH');
        $this->logger->info('$requestedWith: ' . $requestedWith);
        if ($requestedWith === 'XMLHttpRequest') {
            $this->logger->info('ajax');
            $this->view->setTemplateAfter('ajax');
            $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
        } else {
            $this->logger->info('common');
            $this->view->setTemplateAfter('common');
        }*/
        $this->view->setTemplateAfter('common');
        $this->view->dominioPhp = DOMINIO; // así podemos pintar la variable en volt, con los tag de php no lo coge
        $this->view->lang = $this->dispatcher->getParam('language');
        $this->view->languages = ['en' => 'English', 'es' => 'Español', 'de' => 'German'];
        $this->tag->setDefault('select-language', $this->view->lang);
        if (empty($this->dispatcher->getParam('language'))) {
            $this->view->language = $this->request->getBestLanguage();
        } else {
            $this->view->language = $this->__getLanguageFromParam($this->dispatcher->getParam('language'));
        }
        $idiomaExplode = explode('-', $this->request->getBestLanguage());
        if ($this->session->has('Usuario')) {
            $this->view->usuario = $this->session->get('Usuario');
        }
        $this->urlsNoRedirect = ['/usuarios/login', '/usuarios/registro', '/usuarios/restablecer-password/', '/'];
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
                $this->thrown404();
            }
        }
    }

    public function thrown404() {
        // 404 si no existe el idioma
        $response = new \Phalcon\Http\Response();
        $response->setStatusCode(404, "Not Found");
        $this->response->send();
        return $this->dispatcher->forward([
            'controller' => 'errores',
            'action' => 'notFound',
        ]);
        return false;
    }

    private function __checkRouteSlug($slug, $lang) {
        $phql = "SELECT IdiomasCalculadoras.id AS id FROM Calculadoras JOIN IdiomasCalculadoras  
                 WHERE IdiomasCalculadoras.idioma_id = '".$lang."' and  IdiomasCalculadoras.slug = '".$slug."'";
        $manager = $this->modelsManager;
        $result = $manager->executeQuery($phql);
        if (isset($result[0]->id)) {
            return true;
        }
        return false;
    }

    protected function _getTranslation()
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
                case 'de':
                    $idiomaSiglas = 'de';
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
        if (in_array($language, ['es', 'en', 'de'])) return true;
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
            case 'de':
                $languageReturn = 'de-DE';
                break;
            default:
                $languageReturn = 'es-ES';
        }
        return $languageReturn;
    }

    function getUserIP() {
        if (ENVIRONMENT == 'development') {
            $ip = '2.141.83.233';
        } else {
            // Get real visitor IP behind CloudFlare network
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                    $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            }
            $client  = @$_SERVER['HTTP_CLIENT_IP'];
            $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            $remote  = $_SERVER['REMOTE_ADDR'];

            if(filter_var($client, FILTER_VALIDATE_IP)) {
                $ip = $client;
            } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
                $ip = $forward;
            } else {
                $ip = $remote;
            }
        }
        return $ip;
    }

    function getLocationFromIp($ip) {
        if (ENVIRONMENT == 'development') {
            $ip = '2.141.83.233';
        }
        $urlIp = "https://www.iplocate.io/api/lookup/" . $ip;
        $result = file_get_contents($urlIp);
        return $result;
    }
}
