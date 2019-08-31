<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\StringLength;  
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Callback;

class UsuarioRegistroForm extends Form
{
    public function initialize($entity = null, $options = [])
    {
        $email = new Text("email");
        $email->addValidators([
            new PresenceOf([
                'message' => 'El email es requerido.'
            ]),
            new Uniqueness([
                'message' => 'Ese email ya está en uso'
            ]),
        ]);
        $this->add($email);


        $password = new Text("password");
        $password->addValidators([
            new PresenceOf([
                'message' => 'La contraseña es requerida.'
            ])
        ]);
        $this->add($password);

    }
}