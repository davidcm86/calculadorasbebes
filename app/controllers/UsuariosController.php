<?php
class UsuariosController extends ControllerBase
{
    public function loginAjaxAction()
    {
        $this->view->disable();
        $response = new \Phalcon\Http\Response();
        $this->view->setTemplateAfter('ajax');
        $data = $this->AuthPlugin->login($_POST);
        $response->setContent(json_encode($data));
        return $response;
    }
}
