<?php
namespace Util\Validators;
use Util\Validators\ValidatorBase;

class Login extends ValidatorBase
{

    protected $rules = array(
        'username'         =>  'required',
        'password'      =>  'required'
    );

}