<?php


class Paises extends \Phalcon\Mvc\Model
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
    public $country_code;

    /**
     *
     * @var string
     */
    public $country_name;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("calculadoras");
        $this->setSource("paises");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'paises';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Paises[]|Paises|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Paises|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function getPaises()
    {
        $phql = 'SELECT id, country_name FROM Paises ORDER BY country_name';
        $manager = $this->modelsManager;
        $result = $manager->executeQuery($phql);
        return $result;
    }

}
