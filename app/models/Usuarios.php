<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class Usuarios extends \Phalcon\Mvc\Model
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
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $token;

    /**
     *
     * @var string
     */
    public $modified;

    /**
     *
     * @var string
     */
    public $created;

    /**
     *  
     * @var string
     */
    public $last_login;


    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("calculadoras");
        $this->setSource("usuarios");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'usuarios';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuarios[]|Usuarios|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Usuarios|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function beforeModified()
	{
		$this->modified = date('Y-m-d H:i:s');
    }

    public function beforeCreate()
	{
		$this->created = date('Y-m-d H:i:s');
    }

}
