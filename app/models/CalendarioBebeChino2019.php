<?php

class CalendarioBebeChino2019 extends \Phalcon\Mvc\Model
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
    public $edad;

    /**
     *
     * @var integer
     */
    public $mes;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("calculadoras");
        $this->setSource("calendario_bebe_chino_2019");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'calendario_bebe_chino_2019';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CalendarioBebeChino2019[]|CalendarioBebeChino2019|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CalendarioBebeChino2019|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function getSexoBebe($data) {
        $phql = 'SELECT * FROM CalendarioBebeChino2019  WHERE edad = '.$data['tu-edad'].' and mes = '.$data['mes-concepcion-bebe'].'';
        $manager = $this->modelsManager;
        $result = $manager->executeQuery($phql);
        if (isset($result[0]->id)) return 'ninia';
        return 'ninio';
    }

}
