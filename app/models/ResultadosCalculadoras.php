<?php

use Phalcon\Db\RawValue;

class ResultadosCalculadoras extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $campo;

    /**
     *
     * @var string
     */
    public $resultado_fecha;

    /**
     *
     * @var string
     */
    public $resultado_texto;

    /**
     *
     * @var string
     */
    public $calculadora_id;

    /**
     *
     * @var integer
     */
    public $localizacion_usuario_calculadora_id;

    /**
     *
     * @var string
     */
    public $created;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("calculadoras");
        $this->setSource("resultados_calculadoras");
        $this->belongsTo('localizacion_usuario_calculadora_id', 'ResultadosCalculadoras\LocalizacionUsuariosCalculadoras', 'id', ['alias' => 'LocalizacionUsuariosCalculadoras']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'resultados_calculadoras';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ResultadosCalculadoras[]|ResultadosCalculadoras|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ResultadosCalculadoras|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function salvarResultado($datos, $calculadoraId, $idLocalizacion) 
    {
        $resultadosCalculadoras = new ResultadosCalculadoras();
        $resultadosCalculadoras->data = $datos['data-serialize'];
        $resultadosCalculadoras->result = $datos['result-serialize'];
        $resultadosCalculadoras->calculadora_id = $calculadoraId;
        $resultadosCalculadoras->localizacion_usuario_calculadora_id = $idLocalizacion;
        $resultadosCalculadoras->create();
    }

    public function beforeCreate()
	{
		$this->created = new RawValue('now()');
    }

    public function getEstadisticas($calculadoraId) {
        $phql = "SELECT * FROM ResultadosCalculadoras WHERE calculadora_id = $calculadoraId ORDER BY created DESC LIMIT 10";
        $manager = $this->modelsManager;
        $result = $manager->executeQuery($phql);
        return $result;
    }

}
