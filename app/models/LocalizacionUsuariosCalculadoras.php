<?php

class LocalizacionUsuariosCalculadoras extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $calculadora_id;

    /**
     *
     * @var string
     */
    public $idioma_id;

    /**
     *
     * @var string
     */
    public $ip;

    /**
     *
     * @var string
     */
    public $pais;

    /**
     *
     * @var string
     */
    public $ciudad;

    /**
     *
     * @var string
     */
    public $country_code;

    /**
     *
     * @var string
     */
    public $continente;

    /**
     *
     * @var string
     */
    public $latitude;

    /**
     *
     * @var string
     */
    public $longitude;

    /**
     *
     * @var string
     */
    public $postal_code;

    /**
     *
     * @var string
     */
    public $subdivision;

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
        $this->setSource("localizacion_usuarios_calculadoras");
        $this->hasMany('id', 'LocalizacionUsuariosCalculadoras\ResultadosCalculadoras', 'localizacion_usuario_calculadora_id', ['alias' => 'ResultadosCalculadoras']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'localizacion_usuarios_calculadoras';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LocalizacionUsuariosCalculadoras[]|LocalizacionUsuariosCalculadoras|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LocalizacionUsuariosCalculadoras|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function salvarLocalizacion($data, $calculadoraId, $idiomaId) 
    {
        $dataSalvar['calculadora_id'] = $calculadoraId;
        $dataSalvar['idioma_id'] = $idiomaId;
        if (!empty($data->country)) $dataSalvar['pais'] = $data->country;
        if (!empty($data->ip)) $dataSalvar['ip'] = $data->ip;
        if (!empty($data->country_code)) $dataSalvar['country_code'] = $data->country_code;
        if (!empty($data->continent)) $dataSalvar['continente'] = $data->continent;
        if (!empty($data->longitude)) $dataSalvar['longitude'] = $data->longitude;
        if (!empty($data->latitude)) $dataSalvar['latitude'] = $data->latitude;
        if (!empty($data->postal_code)) $dataSalvar['postal_code'] = $data->postal_code;
        if (!empty($data->subdivision)) $dataSalvar['subdivision'] = $data->subdivision;
        if (!empty($data->city)) $dataSalvar['ciudad'] = $data->city;
        $this->create($dataSalvar);
        return $this->id;
    }

    public function beforeCreate()
	{
		$this->created = date('Y-m-d H:i:s');
    }

    public function checkUsuarioCalIp($ip, $calculadoraId) {
        // la calculadora id llega como string ya que los define no admiten numeric
        $phql = 'SELECT * FROM LocalizacionUsuariosCalculadoras  WHERE ip = "'.$ip.'" and calculadora_id = "'.$calculadoraId.'"';
        $manager = $this->modelsManager;
        $result = $manager->executeQuery($phql);
        if (isset($result[0])) return true;
        return false;
    }

}
