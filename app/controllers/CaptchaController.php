<?php

use Gregwar\Captcha\CaptchaBuilder;

class CaptchaController extends \BaseController {

    public function getBuild()
    {
        $builder = new CaptchaBuilder;
		$builder->setIgnoreAllEffects(true);
        $builder->build();
        $captcha = $builder->inline();
        Session::put('captchaPhrase', $builder->getPhrase());
        return $captcha;
    }

}