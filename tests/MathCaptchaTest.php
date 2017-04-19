<?php

use ElicDev\MathCaptcha\MathCaptcha;

class MathCaptchaTest extends PHPUnit_Framework_TestCase
{
    private $captcha;

    public function setUp()
    {
        parent::setUp();

        $this->captcha = new MathCaptcha();
    }

    public function testDisplay()
    {
        $this->assertTrue($this->captcha instanceof MathCaptcha);
    }
}
