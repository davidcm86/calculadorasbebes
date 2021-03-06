<?php

class Calculadoras extends \Phalcon\Mvc\Model
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
    public $img_ruta;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("calculadoras");
        $this->setSource("calculadoras");
        $this->hasMany('id', 'Calculadoras\IdiomasCalculadoras', 'calculadora_id', ['alias' => 'IdiomasCalculadoras']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'calculadoras';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Calculadoras[]|Calculadoras|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Calculadoras|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
