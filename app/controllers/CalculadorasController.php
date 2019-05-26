<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Http\Request;

class CalculadorasController extends ControllerBase
{
    public function indexAction()
    {
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
                    if ($request->isPost()) {
                        // TODO: SEO marcadores de contenido
                        $mensajesError = $this->__comprobarFormEmbarazo($_POST);
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
                            $ipUsuario = $this->getUserIP();
                            if (!empty($ipUsuario)) {
                                // si el usuario ya hizo calculadora y tiene datos, no volver a crearlo
                                $this->LocalizacionUsuariosCalculadoras = new LocalizacionUsuariosCalculadoras();
                                if (!$this->__existeResultadoCalculadora($ipUsuario, CAL_EMBARAZO)) {
                                    $locationUser = $this->getLocationFromIp($ipUsuario);
                                    if (!empty($locationUser)) {
                                        $resultDecode = json_decode($locationUser);
                                        $idLocalizacion = $this->LocalizacionUsuariosCalculadoras->salvarLocalizacion($resultDecode, CAL_EMBARAZO, $language);
                                        $this->ResultadosCalculadoras = new ResultadosCalculadoras();
                                        $data['fecha_ultima_regla'] = $fechaCompleta;
                                        $data['fecha_prevista_parto'] = $fechaPrevistaSave;
                                        $this->ResultadosCalculadoras->salvarResultado($data, CAL_EMBARAZO, $idLocalizacion);
                                    }
                                }
                            }
                            $_POST = [];
                        } else {
                            $this->view->mensajesError = $mensajesError;
                        }
                    }
                    $this->Breadcrumbs->setSeparator('&raquo;&nbsp;');
                    $this->Breadcrumbs->add($t->_('calculadora-embarazo'), null, ['linked' => false]);
                    $this->view->descriptionMeta = $vistaRenderizar . '-meta-description';
                    $this->view->titlePagina = $vistaRenderizar . '-meta-title';
                    $this->view->dias = $this->__getDias();
                    $this->view->meses = $this->__getMeseslanguage($language);
                    $this->view->anios = $this->__getAnios('actualAnterior');
                    $this->view->esMovil = $esMovil;
                    break;
            }
            return $this->view->pick('calculadoras/' . $vistaRenderizar);
        } else {
            $this->thrown404();
        }
    }

    /**
     * Comprobamos si ya existe resultado para esa calculadora e ip del usuario
     */
    private function __existeResultadoCalculadora($ip, $calculadoraId) {
        $params = ['ip' => $ip, 'calculadora_id' => $calculadoraId];
        $result = $this->LocalizacionUsuariosCalculadoras->findFirst($params);
        if (!empty($result)) return true;
        return false;
    }

    private function __comprobarFormEmbarazo($post) {
        $mensajes = [];
        if (empty($post['dia-seleccion-regla'])) $mensajes[] = "error-fecha-dia";
        if (empty($post['mes-seleccion-regla'])) $mensajes[] = "error-fecha-mes";
        if (empty($post['anio-seleccion-regla'])) $mensajes[] = "error-fecha-anio";
        return $mensajes;
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

    private function __getDias() {
        $dias = [
            '01' => 1,
            '02' => 2,
            '03' => 3,
            '04' => 4,
            '05' => 5,
            '06' => 6,
            '07' => 7,
            '08' => 8,
            '09' => 9,
            '10' => 10,
            '11' => 11,
            '12' => 12,
            '13' => 13,
            '14' => 14,
            '15' => 15,
            '16' => 16,
            '17' => 17,
            '18' => 18,
            '19' => 19,
            '20' => 20,
            '21' => 21,
            '22' => 22,
            '23' => 23,
            '24' => 24,
            '25' => 25,
            '26' => 26,
            '27' => 27,
            '28' => 28,
            '29' => 29,
            '30' => 30,
            '31' => 31
        ];
        return $dias;
    }

    private function __getMeseslanguage($language) {
        switch($language) {
            case 'es':
                $meses = [
                    '01' => 'Enero',
                    '02' => 'Febrero',
                    '03' => 'Marzo',
                    '04' => 'Abril',
                    '05' => 'Mayo',
                    '06' => 'Junio',
                    '07' => 'Julio',
                    '08' => 'Agosto',
                    '09' => 'Septiembre',
                    '10' => 'Octubre',
                    '11' => 'Noviembre',
                    '12' => 'Diciembre'
                ];
                break;
            case 'en':
                $meses = [
                    '01' => 'January',
                    '02' => 'February',
                    '03' => 'March',
                    '04' => 'April',
                    '05' => 'May',
                    '06' => 'June',
                    '07' => 'July',
                    '08' => 'August',
                    '09' => 'September',
                    '10' => 'October',
                    '11' => 'November',
                    '12' => 'December'
                ];
                break;
        }
        return $meses;
    }

    // para los diferentes slugs de las calculadoras, agrupamos la vista que se muestra para cada calculadora sabiendo que renderizar
    private function __calculadorasSlugs($slug, $language) {
        $slugsArray = [
            'es' => [
                'embarazo' => 'calculadora-del-embarazo'
            ],
            'en' => [
                'embarazo' => 'pregnancy-calculator'
            ],
        ];
        $vistaRenderizar = array_search($slug, $slugsArray[$language]);
        return $vistaRenderizar;
    }

}
