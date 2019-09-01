<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Http\Request;

class CalculadorasController extends ControllerBase
{
    public function indexAction() {
        //$this->assets->addJs('js/jquery.3.4.1.min.js');
        $this->assets->addJs('js/common.js');
        //$this->assets->addJs('js/jquery.modal.min.js');
        //$modelPaises = new Paises();
        //$this->view->paises = $modelPaises->getPaises();
        /*$usuarioLoginRegistroResultados = '#ex1';
        if ($this->session->has('Usuario')) {
            $usuarioLoginRegistroResultados = 'ver-mas';
        }
        $this->view->usuarioLoginRegistroResultados = $usuarioLoginRegistroResultados;*/
        $esMovil = false;
        if ($this->Mobile_Detect->isMobile()) $esMovil = true;
        $slug = $this->dispatcher->getParam('slug');
        $language = $this->dispatcher->getParam('language');
        $vistaRenderizar = $this->__calculadorasSlugs($slug, $language);
        $t = $this->_getTranslation();
        $this->Breadcrumbs->add($t->_('inicio'), '/'); // breadcrumbs
        $this->view->formAction = '/' . $language . '/' . $slug; // construimos la url del form
        $this->view->rutaHome = '/' . $language;
        if ($vistaRenderizar) {
            $request = new Request();
            switch ($vistaRenderizar) {
                case 'embarazo';
                    $calculadoraId = CAL_EMBARAZO;
                    if ($request->isPost()) {
                        $this->__embarazo($_POST, $language, $calculadoraId);
                    }
                    $cadenaH1Traduccion = 'calculadora-embarazo';
                    $this->view->dias = $this->__getDias();
                    $this->view->meses = $this->__getMeseslanguage($language);
                    $this->view->anios = $this->__getAnios('actualAnterior');
                    break;
                case 'sexo-bebe';
                    $calculadoraId = CAL_SEXO_BEBE;
                    if ($request->isPost()) {
                        $this->__sexoBebe($_POST, $language, $t, $calculadoraId);
                    }
                    $cadenaH1Traduccion = 'calculadora-sexo-bebe';
                    $this->view->meses = $this->__getMeseslanguage($language);
                    $this->view->anios = $this->__getEdadesSexoBebe();
                    $this->__setMovilAndPcForm($esMovil);
                    break;
                case 'color-ojos-bebe';
                    $calculadoraId = CAL_OJOS_BEBE;
                    if ($request->isPost()) {
                        $this->__colorOjosBebe($_POST, $language, $t, $calculadoraId);
                    }
                    $this->__setMovilAndPcForm($esMovil);
                    $cadenaH1Traduccion = 'calculadora-ojos-bebe';
                    $this->view->colorOjos = $this->__colorOjos();
                    break;
                case 'peso-bebe';
                    $calculadoraId = CAL_PESO_BEBE;
                    if ($request->isPost()) {
                        $this->__pesoBebe($_POST, $language, $t, $calculadoraId);
                    }
                    $this->view->semanas = $this->__getSemanasGestacion();
                    $cadenaH1Traduccion = 'calculadora-peso-bebe';
                    break;
                case 'pelo-bebe';
                    $calculadoraId = CAL_PELO_BEBE;
                    if ($request->isPost()) {
                        $this->__peloBebe($_POST, $language, $t, $calculadoraId);
                    }
                    $cadenaH1Traduccion = 'calculadora-pelo-bebe';
                    $this->view->coloresPelo = $this->__getColoresPelolanguage($language);
                    $this->__setMovilAndPcForm($esMovil);
                    break;
            }
            $this->ResultadosCalculadoras = new ResultadosCalculadoras();
            $this->view->estadisticasCalculadora = $this->__formatearResult($this->ResultadosCalculadoras->getEstadisticas($calculadoraId), $calculadoraId, $t, $language);
            $this->view->calculadoraId = $calculadoraId;
            $this->Breadcrumbs->setSeparator('&nbsp;&raquo;&nbsp;');
            $this->Breadcrumbs->add($t->_($cadenaH1Traduccion), null, ['linked' => false]);
            $this->view->descriptionMeta = $vistaRenderizar . '-meta-description';
            $this->view->titlePagina = $vistaRenderizar . '-meta-title';
            return $this->view->pick('calculadoras/' . $vistaRenderizar);
        } else {
            $this->thrown404();
        }
    }

    /**
     * Lógica para calcular la fecha de embarazo
    */
    private function __embarazo($post, $language, $calculadoraId) {
        $mensajesError = $this->__comprobacionFormCalculadorasGenerico($_POST, $calculadoraId);
        if (empty($mensajesError)) {
            $fechaCompleta =  $_POST['anio-seleccion-regla'] . '-' . $_POST['mes-seleccion-regla'] . '-' . $_POST['dia-seleccion-regla'];
            if ($language == 'en') {
                $fechaPrevistaParto = date('Y-m-d', strtotime($fechaCompleta . '+40 week'));
                $fechaPrevistaSave = date('Y-m-d', strtotime($fechaCompleta . '+40 week'));
            } else {
                $fechaPrevistaParto = date('d-m-Y', strtotime($fechaCompleta . '+40 week'));
                $fechaPrevistaSave = date('Y-m-d', strtotime($fechaCompleta . '+40 week'));
            }
            $this->view->fechaPrevistaParto = $fechaPrevistaParto;
            $dataEncode['fecha_ultima_regla'] = $fechaCompleta;
            $data['data-serialize'] = json_encode($dataEncode);
            $data['result-serialize'] = json_encode($fechaPrevistaParto);
            $this->__salvarIpAndResultadoCalculadora($language, CAL_EMBARAZO, $data);
            $_POST = [];
        } else {
            $this->view->mensajesError = $mensajesError;
        }
    }

    /**
    * Lógica para calcular el sexo del bebé
    */
    private function __sexoBebe($post, $language, $t, $calculadoraId) {
        $mensajesError = $this->__comprobacionFormCalculadorasGenerico($_POST, $calculadoraId);
        if (empty($mensajesError)) {
            $this->CalendarioBebeChino2019 = new CalendarioBebeChino2019();
            $sexoBebe = $this->CalendarioBebeChino2019->getSexoBebe($_POST);
            $dataEncode['edad_mama'] = $post['tu-edad'];
            $dataEncode['mes_concepcion_bebe'] = $post['mes-concepcion-bebe'];
            $data['data-serialize'] = json_encode($dataEncode);
            $data['result-serialize'] = json_encode($sexoBebe);
            $this->__salvarIpAndResultadoCalculadora($language, CAL_SEXO_BEBE, $data);
            $this->view->sexo = $t->_($sexoBebe);
            $_POST = [];
        } else {
            $this->view->mensajesError = $mensajesError;
        }
    }

    /**
    * Lógica para calcular el sexo del bebé
    */
    private function __colorOjosBebe($post, $language, $t, $calculadoraId) {
        $mensajesError = $this->__comprobacionFormCalculadorasGenerico($post, $calculadoraId);
        if (empty($mensajesError)) {
            $porcentajeMarron = 0;
            $porcentajeVerde = 0;
            $porcentajeAzul = 0;
            if ($post['color-ojos-mama'] == 'marron' && $post['color-ojos-papa'] == 'marron') {
                $porcentajeMarron = 75;
                $porcentajeVerde = 18.75;
                $porcentajeAzul = 6.25;
            } else if ($post['color-ojos-mama'] == 'marron' && $post['color-ojos-papa'] == 'verde' 
                || $post['color-ojos-papa'] == 'marron' && $post['color-ojos-mama'] == 'verde') {
                $porcentajeMarron = 50;
                $porcentajeVerde = 37.5;
                $porcentajeAzul = 12.5;
            } else if ($post['color-ojos-mama'] == 'marron' && $post['color-ojos-papa'] == 'azul' || 
                $post['color-ojos-papa'] == 'marron' && $post['color-ojos-mama'] == 'azul') {
                $porcentajeMarron = 50;
                $porcentajeAzul = 50;
            } else if ($post['color-ojos-mama'] == 'verde' && $post['color-ojos-papa'] == 'verde') {
                $porcentajeMarron = '<1';
                $porcentajeVerde = 75;
                $porcentajeAzul = 25;
            } else if ($post['color-ojos-mama'] == 'verde' && $post['color-ojos-papa'] == 'azul' || 
                $post['color-ojos-papa'] == 'verde' && $post['color-ojos-mama'] == 'azul') {
                $porcentajeMarron = 0;
                $porcentajeVerde = 50;
                $porcentajeAzul = 50;
            } else if ($post['color-ojos-mama'] == 'azul' && $post['color-ojos-papa'] == 'azul') {
                $porcentajeMarron = 0;
                $porcentajeVerde = 1;
                $porcentajeAzul = 99;
            }
            $dataEncode['color_ojos_mama'] = $post['color-ojos-mama'];
            $dataEncode['color_ojos_papa'] = $post['color-ojos-papa'];
            $data['data-serialize'] = json_encode($dataEncode);
            $dataResultEncode['marron'] = $porcentajeMarron;
            $dataResultEncode['verde'] = $porcentajeVerde;
            $dataResultEncode['azul'] = $porcentajeAzul;
            $data['result-serialize'] = json_encode($dataResultEncode);
            $this->__salvarIpAndResultadoCalculadora($language, CAL_OJOS_BEBE, $data);
            $_POST = [];
            $this->view->marron = $porcentajeMarron;
            $this->view->verde = $porcentajeVerde;
            $this->view->azul = $porcentajeAzul;
        } else {
            $this->view->mensajesError = $mensajesError;
        }
    }

    /**
     * Lógica para calcular el peso del bebé
     */
    public function __pesoBebe($post, $language, $t, $calculadoraId) {
        $mensajesError = $this->__comprobacionFormCalculadorasGenerico($post, $calculadoraId);
        if (empty($mensajesError)) {
            $dataEncode['semana'] = $post['semana'];
            $data['data-serialize'] = json_encode($dataEncode);
            $semanasPeso = [
                '11' => 8, '12' => 14, '13' => 20, '14' => 40, '15' => 65, '16' => 85, '17' => 110, '18' => 150, '19' => 190,
                '20' => 240, '21' => 300, '22' => 360, '23' => 430, '24' => 600, '25' => 680, '26' => 780, '27' => 900, '28' => 1050,
                '29' => 1180, '30' => 1250, '31' => 1400, '32' => 1600, '33' => 1750, '34' => 2000, '35' => 2250, '36' => 2500, 
                '37' => 2800, '38' => 3000, '39' => 3200, '40' => 3400
            ];
            $dataResultEncode['peso'] = $semanasPeso[$post['semana']];
            $data['result-serialize'] = json_encode($dataResultEncode);
            $this->__salvarIpAndResultadoCalculadora($language, CAL_PESO_BEBE, $data);
            $_POST = [];
            $this->view->peso = $semanasPeso[$post['semana']];
        } else {
            $this->view->mensajesError = $mensajesError;
        }
    }

    /**
    * Lógica para calcular el color pelo del bebé
    */
    private function __peloBebe($post, $language, $t, $calculadoraId) {
        $mensajesError = $this->__comprobacionFormCalculadorasGenerico($_POST, $calculadoraId);
        if (empty($mensajesError)) {
            $porcentajes = $this->__comprobarColorPelo($_POST);
            $sexoBebe = $this->CalendarioBebeChino2019->getSexoBebe($_POST);
            //$dataEncode['edad_mama'] = $post['tu-edad'];
            //$dataEncode['mes_concepcion_bebe'] = $post['mes-concepcion-bebe'];
            //$data['data-serialize'] = json_encode($dataEncode);
            //$data['result-serialize'] = json_encode($sexoBebe);
            //$this->__salvarIpAndResultadoCalculadora($language, CAL_SEXO_BEBE, $data);
            $this->view->sexo = $t->_($sexoBebe);
            $_POST = [];
        } else {
            $this->view->mensajesError = $mensajesError;
        }
    }

    /**
     * Sacamos el porcentaje correspondiente a los colores de pelo que nos pasan
     */
    private function __comprobarColorPelo($post)
    {
        $porcentajes['negro'] = 0;
        $porcentajes['castanio'] = 0;
        $porcentajes['pelirrojo'] = 0;
        $porcentajes['castanio-claro'] = 0;
        $porcentajes['rubio'] = 0;
        // negro
        if ($post['color-pelo-mama'] == 'negro' && $post['color-pelo-papa'] == 'negro') {
            $porcentajes['negro'] = 100;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'negro' && $post['color-pelo-papa'] == 'castanio') 
            || ($post['color-pelo-papa'] == 'negro' && $post['color-pelo-mama'] == 'castanio')) {
            $porcentajes['negro'] = 50;
            $porcentajes['castanio'] = 50;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'negro' && $post['color-pelo-papa'] == 'pelirrojo') 
            || ($post['color-pelo-papa'] == 'negro' && $post['color-pelo-mama'] == 'pelirrojo')) {
            $porcentajes['castanio'] = 100;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'negro' && $post['color-pelo-papa'] == 'castanio-claro') 
            || ($post['color-pelo-papa'] == 'negro' && $post['color-pelo-mama'] == 'castanio-claro')) {
            $porcentajes['castanio'] = 100;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'negro' && $post['color-pelo-papa'] == 'rubio') 
            || ($post['color-pelo-papa'] == 'negro' && $post['color-pelo-mama'] == 'rubio')) {
            $porcentajes['castanio'] = 100;
            return $porcentajes;
        }
        // castaño
        if ($post['color-pelo-mama'] == 'castanio' && $post['color-pelo-papa'] == 'castanio') {
            $porcentajes['negro'] = 25;
            $porcentajes['castanio'] = 50;
            $porcentajes['pelirrojo'] = 3;
            $porcentajes['castanio-claro'] = 11;
            $porcentajes['rubio'] = 12;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'castanio' && $post['color-pelo-papa'] == 'negro') 
            || ($post['color-pelo-papa'] == 'castanio' && $post['color-pelo-mama'] == 'negro')) {
            $porcentajes['negro'] = 50;
            $porcentajes['castanio'] = 50;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'castanio' && $post['color-pelo-papa'] == 'pelirrojo') 
            || ($post['color-pelo-papa'] == 'castanio' && $post['color-pelo-mama'] == 'pelirrojo')) {
            $porcentajes['pelirrojo'] = 16;
            $porcentajes['castanio'] = 50;
            $porcentajes['castanio-claro'] = 34;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'castanio' && $post['color-pelo-papa'] == 'castanio-claro') 
            || ($post['color-pelo-papa'] == 'castanio' && $post['color-pelo-mama'] == 'castanio-claro')) {
            $porcentajes['pelirrojo'] = 8;
            $porcentajes['castanio'] = 50;
            $porcentajes['castanio-claro'] = 25;
            $porcentajes['rubio'] = 16;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'castanio' && $post['color-pelo-papa'] == 'rubio') 
            || ($post['color-pelo-papa'] == 'castanio' && $post['color-pelo-mama'] == 'rubio')) {
            $porcentajes['castanio'] = 50;
            $porcentajes['castanio-claro'] = 16;
            $porcentajes['rubio'] = 34;
            return $porcentajes;
        }
        // pelirrojo
        if ($post['color-pelo-mama'] == 'pelirrojo' && $post['color-pelo-papa'] == 'pelirrojo') {
            $porcentajes['pelirrojo'] = 100;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'pelirrojo' && $post['color-pelo-papa'] == 'castanio-claro') 
            || ($post['color-pelo-papa'] == 'pelirrojo' && $post['color-pelo-mama'] == 'castanio-claro')) {
            $porcentajes['pelirrojo'] = 50;
            $porcentajes['castanio-claro'] = 50;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'pelirrojo' && $post['color-pelo-papa'] == 'rubio') 
            || ($post['color-pelo-papa'] == 'pelirrojo' && $post['color-pelo-mama'] == 'rubio')) {
            $porcentajes['castanio-claro'] = 100;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'pelirrojo' && $post['color-pelo-papa'] == 'negro') 
            || ($post['color-pelo-papa'] == 'pelirrojo' && $post['color-pelo-mama'] == 'negro')) {
            $porcentajes['castanio'] = 100;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'pelirrojo' && $post['color-pelo-papa'] == 'castanio') 
            || ($post['color-pelo-papa'] == 'pelirrojo' && $post['color-pelo-mama'] == 'castanio')) {
            $porcentajes['castanio'] = 50;
            $porcentajes['pelirrojo'] = 16;
            $porcentajes['castanio-claro'] = 34;
            return $porcentajes;
        }
        // castaño claro
        if ($post['color-pelo-mama'] == 'castanio-claro' && $post['color-pelo-papa'] == 'castanio-claro') {
            $porcentajes['pelirrojo'] = 25;
            $porcentajes['castanio-claro'] = 50;
            $porcentajes['rubio'] = 25;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'castanio-claro' && $post['color-pelo-papa'] == 'rubio')
            || ($post['color-pelo-papa'] == 'castanio-claro' && $post['color-pelo-mama'] == 'rubio')) {
            $porcentajes['castanio-claro'] = 50;
            $porcentajes['rubio'] = 50;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'castanio-claro' && $post['color-pelo-papa'] == 'negro')
            || ($post['color-pelo-papa'] == 'castanio-claro' && $post['color-pelo-mama'] == 'negro')) {
            $porcentajes['castanio'] = 100;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'castanio-claro' && $post['color-pelo-papa'] == 'castanio')
            || ($post['color-pelo-papa'] == 'castanio-claro' && $post['color-pelo-mama'] == 'castanio')) {
            $porcentajes['castanio'] = 50;
            $porcentajes['pelirrojo'] = 8;
            $porcentajes['castanio-claro'] = 25;
            $porcentajes['rubio'] = 17;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'castanio-claro' && $post['color-pelo-papa'] == 'pelirrojo')
            || ($post['color-pelo-papa'] == 'castanio-claro' && $post['color-pelo-mama'] == 'pelirrojo')) {
            $porcentajes['pelirrojo'] = 50;
            $porcentajes['castanio-claro'] = 50;
            return $porcentajes;
        }
        // rubio
        if ($post['color-pelo-mama'] == 'rubio' && $post['color-pelo-papa'] == 'rubio') {
            $porcentajes['rubio'] = 100;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'rubio' && $post['color-pelo-papa'] == 'negro')
            || ($post['color-pelo-papa'] == 'rubio' && $post['color-pelo-mama'] == 'negro')) {
            $porcentajes['castanio'] = 100;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'rubio' && $post['color-pelo-papa'] == 'castanio')
            || ($post['color-pelo-papa'] == 'rubio' && $post['color-pelo-mama'] == 'castanio')) {
            $porcentajes['castanio'] = 50;
            $porcentajes['castanio-claro'] = 16;
            $porcentajes['rubio'] = 34;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'rubio' && $post['color-pelo-papa'] == 'pelirrojo')
            || ($post['color-pelo-papa'] == 'rubio' && $post['color-pelo-mama'] == 'pelirrojo')) {
            $porcentajes['castanio-claro'] = 100;
            return $porcentajes;
        }
        if (($post['color-pelo-mama'] == 'rubio' && $post['color-pelo-papa'] == 'pelirrojo')
            || ($post['color-pelo-papa'] == 'rubio' && $post['color-pelo-mama'] == 'pelirrojo')) {
            $porcentajes['castanio-claro'] = 50;
            $porcentajes['rubio'] = 50;
            return $porcentajes;
        }
    }
    
    /**
     * __comprobacionFormCalculadorasGenerico
     *
     * @param  mixed $post Los datos que vienen del form
     * @param  mixed $calculadoraId
     *
     * @return void
     */
    private function __comprobacionFormCalculadorasGenerico($post, $calculadoraId) {
        $mensajes = [];
        switch ($calculadoraId) {
            case CAL_EMBARAZO:
                if (empty($post['dia-seleccion-regla']) || !is_numeric($post['dia-seleccion-regla'])) $mensajes[] = "error-fecha-dia";
                if (empty($post['mes-seleccion-regla']) || !is_numeric($post['mes-seleccion-regla'])) $mensajes[] = "error-fecha-mes";
                if (empty($post['anio-seleccion-regla']) || !is_numeric($post['anio-seleccion-regla'])) $mensajes[] = "error-fecha-anio";
                break;
            case CAL_SEXO_BEBE:
                if (empty($post['tu-edad']) || !is_numeric($post['tu-edad'])) $mensajes[] = "error-tu-edad";
                if (empty($post['mes-concepcion-bebe']) || !is_numeric($post['mes-concepcion-bebe'])) $mensajes[] = "error-mes-concepcion-bebe";
                break;
            case CAL_OJOS_BEBE:
                if (empty($post['color-ojos-mama'])) $mensajes[] = "error-color-ojos-mama";
                if (empty($post['color-ojos-papa'])) $mensajes[] = "error-color-ojos-papa";
                break;
            case CAL_PESO_BEBE:
                if (empty($post['semana'])) $mensajes[] = "error-semana";
                break;
            case CAL_PELO_BEBE:
                if (empty($post['color-pelo-mama'])) $mensajes[] = "error-color-pelo-mama";
                if (empty($post['color-pelo-papa'])) $mensajes[] = "error-color-pelo-papa";
                break;
        }
        return $mensajes;
    }
    

    /**
     * Salvamos la ip y localización del usuario y la data y el resul de su form
     */
    private function __salvarIpAndResultadoCalculadora($language, $calculadoraId, $data) {
        $ipUsuario = $this->getUserIP();
        if (!empty($ipUsuario)) {
            // si el usuario ya hizo calculadora y tiene datos, no volver a crearlo
            $this->LocalizacionUsuariosCalculadoras = new LocalizacionUsuariosCalculadoras();
            if (!$this->__existeResultadoCalculadora($ipUsuario, $calculadoraId)) {
                $locationUser = $this->getLocationFromIp($ipUsuario);
                if (!empty($locationUser)) {
                    $resultDecode = json_decode($locationUser);
                    $idLocalizacion = $this->LocalizacionUsuariosCalculadoras->salvarLocalizacion($resultDecode, $calculadoraId, $language);
                    $this->ResultadosCalculadoras = new ResultadosCalculadoras();
                    $this->ResultadosCalculadoras->salvarResultado($data, $calculadoraId, $idLocalizacion);
                }
            }
        }
    }

    /**
     * Comprobamos si ya existe resultado para esa calculadora e ip del usuario
     */
    private function __existeResultadoCalculadora($ip, $calculadoraId) {
        if ($this->LocalizacionUsuariosCalculadoras->checkUsuarioCalIp($ip, $calculadoraId)) return true;
        return false;
    }

    private function __getAnios($tipo) {
        switch($tipo) {
            case 'actualAnterior':
                $anios = [date('Y') => date('Y'), date('Y', strtotime('-1 year')) => date('Y', strtotime('-1 year'))];
                break;
            default:
                $anios = [date('Y') => date('Y'), date('Y', strtotime('-1 year')) => date('Y', strtotime('-1 year'))];
        }
        return $anios;
    }

    private function __colorOjos() {
        $colorOjos = ['azul' => 'Azúl', 'marron' => 'Marrón', 'verde' => 'Verde'];
        return $colorOjos;
    }

    private function __getDias() {
        $dias = [
            '01' => 1, '02' => 2, '03' => 3, '04' => 4, '05' => 5, '06' => 6, '07' => 7, '08' => 8, '09' => 9, '10' => 10,
            '11' => 11, '12' => 12, '13' => 13, '14' => 14, '15' => 15, '16' => 16, '17' => 17, '18' => 18, '19' => 19, '20' => 20,
            '21' => 21, '22' => 22, '23' => 23, '24' => 24, '25' => 25, '26' => 26, '27' => 27, '28' => 28, '29' => 29,
            '30' => 30, '31' => 31
        ];
        return $dias;
    }

    private function __getMeseslanguage($language) {
        switch($language) {
            case 'es':
                $meses = [
                    '01' => 'Enero', '02' => 'Febrero', '03' => 'Marzo', '04' => 'Abril', '05' => 'Mayo', '06' => 'Junio',
                    '07' => 'Julio', '08' => 'Agosto', '09' => 'Septiembre', '10' => 'Octubre', '11' => 'Noviembre',
                    '12' => 'Diciembre'
                ];
                break;
            case 'en':
                $meses = [
                    '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May',
                    '06' => 'June', '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October',
                    '11' => 'November', '12' => 'December'
                ];
                break;
        }
        return $meses;
    }

    private function __getColoresPelolanguage($language)
    {
        switch($language) {
            case 'es':
                $colores = [
                    'negro' => 'Negro', 'castaño' => 'Castaño', 'pelirrojo' => 'Pelirrojo', 'castaño-claro' => 'Castaño claro', 'rubio' => 'Rubio'
                ];
                break;
            case 'en':
                $colores = [
                    'negro' => 'Negro', 'castaño' => 'Castaño', 'pelirrojo' => 'Pelirrojo', 'castaño-claro' => 'Castaño claro', 'rubio' => 'Rubio'
                ];
                break;
        }
        return $colores;
    }

    private function __getEdadesSexoBebe() {
        $anios = [
            '18' => 18, '19' => 19, '20' => 20, '21' => 21, '22' => 22, '23' => 23, '24' => 24, '25' => 25, '26' => 26,
            '27' => 27, '28' => 28, '29' => 29, '30' => 30, '31' => 31, '32' => 32, '33' => 33, '34' => 34, '35' => 35,
            '36' => 36, '37' => 37, '38' => 38, '39' => 39, '40' => 40, '41' => 41, '42' => 42, '43' => 43,'44' => 44
        ];
        return $anios;
    }

    private function __getSemanasGestacion() {
        for ($i = 11; $i < 41; $i++) {
            $semanas[$i] = $i;
        }
        return $semanas;
    }

    /**
     * Para los diferentes slugs de las calculadoras, agrupamos la vista que se muestra para cada calculadora sabiendo que renderizar,
     * debido a distintos idiomas.
     * Si no existe el slug tiramos 404
     */
    private function __calculadorasSlugs($slug, $language) {
        $slugsArray = [
            'es' => [
                'embarazo' => 'calculadora-del-embarazo',
                'sexo-bebe' => 'calculadora-sexo-bebe',
                'color-ojos-bebe' => 'calculadora-color-ojos-bebe',
                'peso-bebe' => 'calculadora-peso-bebe',
                'pelo-bebe' => 'calculadora-color-pelo-bebe'
            ],
            'en' => [
                'embarazo' => 'pregnancy-calculator',
                'sexo-bebe' => 'baby-sex-calculator',
                'color-ojos-bebe' => 'baby-eyes-color-calculator',
                'peso-bebe' => 'baby-weight-calculator',
                'pelo-bebe' => 'baby-hair-color-calculator'
            ],
        ];
        $vistaRenderizar = array_search($slug, $slugsArray[$language]);
        return $vistaRenderizar;
    }

    /**
     * Dependiendo del form y de si estamos en pc o movil, se mostrará el form colapsado o no.
     */
    private function __setMovilAndPcForm($esMovil) {
        $form = 'formCollapsed';
        $class = 'select formCollapsed-item formCollapsed-itemPrimary';
        if ($esMovil) {
            $form = 'form';
            $class = 'select select-fullWidth';
        }
        $this->view->form = $form;
        $this->view->class = $class;
    }

    /**
     * Formateamos el resultado de la calculadora según queramos dependiendo de la calculadora y si necesita traducción o no.
     * en phalcon no se puede modificar el resulset de la query a no ser que devuelvas un array. Esto es así debido
     * a que hace al framework mucho más rápido entre otras cosas.
     */
    private function __formatearResult($data, $calculadoraId, $t, $language) {
        switch ($calculadoraId) {
            case CAL_EMBARAZO;
                foreach ($data as $key => $field) {
                    $data[$key]['created'] = date('Y-m-d H:i:s', strtotime($field['created']));
                    if ($language != 'en') $data[$key]['created'] = date('d-m-Y H:i:s', strtotime($field['created']));
                    $data[$key]['result'] = json_decode($field['result']);
                    if ($language == 'en') $data[$key]['result'] = date('Y-m-d', strtotime($data[$key]['result']));
                    $ultimaRegla = json_decode($field['data']);
                    $data[$key]['data'] = $ultimaRegla->fecha_ultima_regla;
                    if ($language != 'en') $data[$key]['data'] = date('d-m-Y', strtotime($ultimaRegla->fecha_ultima_regla));
                }
                break;
            case CAL_SEXO_BEBE;
                foreach ($data as $key => $field) {
                    $data[$key]['created'] = date('Y-m-d H:i:s', strtotime($field['created']));
                    if ($language != 'en') $data[$key]['created'] = date('d-m-Y H:i:s', strtotime($field['created']));
                    $data[$key]['result'] = $t->_(json_decode($field['result']));
                    $dataIntroducido = json_decode($field['data']);
                    $data[$key]['edad_mama'] = $dataIntroducido->edad_mama;
                    $meses = $this->__getMeseslanguage($language);
                    $data[$key]['mes_concepcion_bebe'] = $meses[$dataIntroducido->mes_concepcion_bebe];
                }
                break;
            case CAL_OJOS_BEBE;
                foreach ($data as $key => $field) {
                    $data[$key]['created'] = date('Y-m-d H:i:s', strtotime($field['created']));
                    if ($language != 'en') $data[$key]['created'] = date('d-m-Y H:i:s', strtotime($field['created']));
                    $resultData = json_decode($field['result']);
                    $data[$key]['marron'] = $resultData->marron;
                    $data[$key]['verde'] = $resultData->verde;
                    $data[$key]['azul'] = $resultData->azul;
                    $dataIntroducido = json_decode($field['data']);
                    $data[$key]['color_ojos_mama'] = $t->_($dataIntroducido->color_ojos_mama);
                    $data[$key]['color_ojos_papa'] = $t->_($dataIntroducido->color_ojos_papa);
                }
                break;
            case CAL_PESO_BEBE;
                foreach ($data as $key => $field) {
                    $data[$key]['created'] = date('Y-m-d', strtotime($field['created']));
                    if ($language != 'en') $data[$key]['created'] = date('d-m-Y', strtotime($field['created']));
                    $resultData = json_decode($field['result']);
                    $data[$key]['peso'] = $resultData->peso;
                    $dataIntroducido = json_decode($field['data']);
                    $data[$key]['semana'] = $dataIntroducido->semana;
                }
                break;
        }
        // volvemos al objeto para que VOLT pueda renderizar el resultado
        $datos = json_decode(json_encode($data), FALSE);
        return $datos;
    }

}
