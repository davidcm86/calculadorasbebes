<?php
class UsuariosController extends ControllerBase
{
    public function loginAjaxAction()
    {
        $this->logger->info('1');
        $this->view->disable();
        $response = new \Phalcon\Http\Response();
        $this->view->setTemplateAfter('ajax');
        $usuario = $this->session->get('Usuario');
        $this->logger->info('2');
        if (!$usuario) {
            $this->logger->info('3');
            $this->__verificarUrlAnterior();
            $this->logger->info('4');
            $result = $this->AuthPlugin->login($_POST);
            $this->logger->info('5');
            if (!isset($result['errores']) || empty($result['errores'])) {
                $this->logger->info('6');
                $UrlAnterior = $this->session->get('UrlAnterior');
                $result['UrlAnterior'] = '/';
                if ($UrlAnterior) $result['UrlAnterior'] = $UrlAnterior;
            } else {
                $this->logger->info('7');
                $this->logger->info($result['errores']);
            }
        } else {
            $this->logger->info('redirect');
            $result['status'] = 'ok';
            $result['UrlAnterior'] = '/';
        }
        $response->setContent(json_encode($result));
        return $response;
    }

    public function registroAjaxAction()
    {
        $this->view->disable();
        $response = new \Phalcon\Http\Response();
        $this->view->setTemplateAfter('ajax');
        $data = $this->AuthPlugin->registro($_POST);
        $response->setContent(json_encode($data));
        return $response;
    }

    public function logoutAction()
    {
        $this->view->disable();
        $this->AuthPlugin->logout();
    }

    /**
     * Si la url anterior es distinta del array que no permitimos, metemos en session para redirigir mÃ¡s tarde
     */
    private function __verificarUrlAnterior()
    {
        if (!empty($_SERVER['HTTP_REFERER']) && !in_array(str_replace(DOMINIO, '', $_SERVER['HTTP_REFERER']), $this->urlsNoRedirect)) {
            $this->session->set('UrlAnterior', $_SERVER['HTTP_REFERER']);    
        }
    }
}
