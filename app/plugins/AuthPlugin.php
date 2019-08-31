<?php

use Phalcon\Mvc\User\Plugin;
use Phalcon\Http\Response;
use Phalcon\Security\Random;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;

use UsuarioRegistroForm;

class AuthPlugin extends Plugin
{
    public function registro($data, $fuenteRegistro = 'web')
	{
        $return = array();
        $usuario = new Usuarios();
        $form = new UsuarioRegistroForm;
        $return['status'] = 'ok';
        if ($fuenteRegistro == 'web') {
            // Validate the form
            if (!$form->isValid($data, $usuario)) {
                foreach ($form->getMessages() as $message) {
                    $return['errores'][] = $message->getMessage() . '</br>';
                    $return['status'] = 'error';
                }
            } else {
                $usuario->password = $this->security->hash($data["password"]);
                $usuario->email = $data["email"];
                $usuario->pais_id = $data['paisId'];
                $return = $this->__salvarRegistro($usuario, $return, true);
            }
        } else {
            // si existe correo, el usuario hizo registro web, hacer login del usuario red social.
            $parameters = ["email = '".$data['email']."'"];
            $existeUsuario = $usuario::findFirst($parameters);
            // si existe correo sin verificar y hace login con red social, se pone como verificado (campo activado)
            if ($existeUsuario) {
                $existeUsuario->activado = 1;
                $existeUsuario->save();
                $this->__setSessionLoginUsuario($existeUsuario);
            } else {
                $usuario->fuente_registro = $fuenteRegistro;
                $usuario->email = $data['email'];
                $usuario->nombre = $data['given_name'];
                $usuario->apellidos = $data['family_name'];
                $usuario->activado = 1;
                $return = $this->__salvarRegistro($usuario, $return);
            }
        }
        return $return;
    }

    /**
     * Seteamos las sessiones del usuario para hacer login
     */
    private function __setSessionLoginUsuario($usuario) {
        $sessionUsuario = ['id' => $usuario->id, 'email' => $usuario->email];
        $usuario->last_login = date('Y-m-d H:i:s');
        $usuario->save();
        $this->session->set('Usuario', $sessionUsuario);
        return;
    }

    /**
     * Salvamos el registro del usuarios independientemente de la fuente que venga (web, fb, google)
     */
    private function __salvarRegistro($usuario, $return, $mandarEmailActivacion = false) {
        $this->logger->info('a');
        $usuario->email = strtolower($usuario->email);
        if (!$usuario->create()) {
            $return['errores'][] = 'No se ha podido crear el registro. Pruebe más tarde.';
            $this->logger->error('Al crear registro desde Auth');
        } else {
            $this->logger->info('b');
            $parameters = ["email =  '$usuario->email'"];
            $user = $usuario::findFirst($parameters);
            if ($user) {
                $this->logger->info('c');
                //$this->__setSessionLoginUsuario($user);
                $this->__mandarEmailActivacionCuenta($user->email);
            } else {
                $this->logger->error('Error inesperado al hacer login');
                $return['errores'][] = 'Email o contraseña incorrectos';
            }
        }
        return $return;
    }
    
    public function login($data, $procedencia = null)
	{
        if (empty($procedencia)) {
            $return = array();
            $validation = new Validation();
            $validation->add('email',new PresenceOf(['message' => 'El email es requerido']));
            $validation->add('email', new Email(['message' => 'El email no es válido']));
            $messages = $validation->validate($_POST);
            if (count($messages)) {
                foreach ($messages as $message) {
                    $return['errores'][] = $message->getMessage();
                }
            } else {
                $usuarios = new Usuarios();
                $parameters = ["email = '".$data['email']."'"];
                $usuario = $usuarios::findFirst($parameters);
                if ($this->security->checkHash($data['password'], $usuario->password)) {
                    if ($usuario) {
                        $this->__setSessionLoginUsuario($usuario);
                        $return['status'] = 'ok';
                    } else {
                        $return['status'] = 'error';
                        $return['errores'] = 'Email o contraseña incorrectos';
                    }
                } else {
                    $return['status'] = 'error';
                    $return['errores'] = 'Email o contraseña incorrectos';
                }
            }
        } elseif ($procedencia == 'google') {
            $usuarios = new Usuarios();
            $parameters = ["email = '".$data['email']."'"];
            $usuario = $usuarios::findFirst($parameters);
            if ($usuario) {
                $this->__setSessionLoginUsuario($usuario);
            } else {
                $return['errores'][] = 'Email o contraseña incorrectos';
            }
        }
        $this->logger->info('el return');
        return $return;
    }
    
    public function logout()
	{
        $this->session->remove('Usuario');
        return $this->response->redirect('/');
    }

    public function registroConFacebook() 
    {
        $helper = $this->Facebook->getRedirectLoginHelper();
        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }
        try {
            if (!$this->session->has('fb_access_token')) {
                $accessToken = $helper->getAccessToken();
                $this->session->set('fb_access_token', $accessToken);
            } else {
                $accessToken = $this->session->get('fb_access_token');
            }
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        // si cualquier tipo de error
        if (!isset($accessToken)) {
            $this->logger->error('Error inesperado en FB Registro, no hay token');
            $this->response->redirect('/');
        }
        if ($accessToken) {
            $response = $this->Facebook->get('/me?fields=id,name,email,first_name,last_name', $accessToken);
            $user = $response->getGraphUser();
            if ($user) {
                try {
                    $result = $this->registro($user, 'fb');
                    if (empty($result['errores'])) {
                        $UrlAnterior = $this->session->get('UrlAnterior');
                        if ($UrlAnterior) {
                            return $this->response->redirect($UrlAnterior);
                        }
                        return $this->response->redirect('/');
                    } else {
                        return $result;
                    }
                } catch(FacebookApiException $e) {
                    $user = NULL;
                    return $this->response->redirect('/usuarios/login');
                }
            }
            exit;
        }
    }

    public function registroConGoogle($usuarioData) {
        $result = $this->registro($usuarioData, 'google');
        if (empty($result['errores'])) {
            $UrlAnterior = $this->session->get('UrlAnterior');
            if ($UrlAnterior) {
                return $this->response->redirect($UrlAnterior);
            }
            return $this->response->redirect('/');
        } else {
            return $result;
        }
    }

    public function recuperarPassword($data) {
        $return = array();
        $usuariosModel = new Usuarios();
        $parameters = ["email = '".$data['email']."'"];
        $usuario = $usuariosModel::findFirst($parameters);
        if ($usuario) {
            if ($usuario->activado == 1) {
                $tokenUsuariosModel = new TokenUsuarios();
                $tokenGenerado = $tokenUsuariosModel->generarToken(1, $usuario->id);
                $return['errores'][] = "Te hemos enviado un email para restablecer tu contraseña a la direcciÃ³n de correo: " . $usuario->email;           
                $dataEmail = [
                    'urlRestablecerPassword' => DOMINIO . '/usuarios/restablecer-password/' . $tokenGenerado . '/' . $this->CommonPlugin->encriptar($usuario->id)
                ];
                $this->MailerPlugin->enviarEmail('usuario/recuperar_password', $usuario->email, $dataEmail);
            } else {
                // TODO: poner enlace de recuperar password
                $return['errores'][] = 'Antes de recuperar tu password debes activar tu cuenta desde <a href="">este enlace</a>';
            }
        } else {
            $return['errores'][] = 'Lo sentimos, no hemos encontrada esa direcciÃ³n de correo.';
        }
        return $return;
    }

    /**
     * Podemos tener varios casos.
     * 1.-Registro en la web.
     * 2.-Teniendo hecho registro con fb/google que lo hagan por web, se le pone la pass y se le envia email activacion.
     * 3.-Nos piden de nuevo el email de activaciÃ³n desde un enlace en la web.
     */
    // TODO: crear plantilla y que funcione el email
    private function __mandarEmailActivacionCuenta($email) {

    }

    private function __reenviarEmailActiavacionCuenta($email) {

    }
}