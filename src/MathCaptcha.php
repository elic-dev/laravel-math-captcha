<?php

namespace ElicDev\MathCaptcha;

use Illuminate\Session\SessionManager;
use Illuminate\Support\Arr;

class MathCaptcha
{
    /**
     * @var SessionManager
     */
    private $session;

    /**
     *
     * @param SessionManager|null $session
     */
    public function __construct(SessionManager $session = null)
    {
        $this->session = $session;
    }

    /**
     * Returns the math question as string. The second operand is always a larger
     * number then the first one. So it's on first position because we don't want
     * any negative results.
     *
     * @return string
     */
    public function label()
    {
        if(config('math-captcha.text')) {
            return sprintf("%s %s %s",
                trans('mathcaptcha::math-captcha.numbers.' . $this->getMathSecondOperator()), 
                trans('mathcaptcha::math-captcha.operands.' . $this->getMathOperand()),
                trans('mathcaptcha::math-captcha.numbers.' . $this->getMathFirstOperator())
            );
        } else {
            return sprintf("%d %s %d", $this->getMathSecondOperator(), $this->getMathOperand(), $this->getMathFirstOperator());
        }
    }

    /**
     * Returns the math input field
     * @param  array  $attributes Additional HTML attributes
     * @return string the input field
     */
    public function input(array $attributes = [])
    {
        $default = [];
        $default['type'] = 'text';
        $default['id'] = 'mathcaptcha';
        $default['name'] = 'mathcaptcha';
        $default['required'] = 'required';
        $default['value'] = old('mathcaptcha');

        $attributes = array_merge($default, $attributes);

        $html = '<input ' . $this->buildAttributes($attributes) . '>';

        return $html;
    }

    /**
     * Laravel input validation
     * @param  string $value
     * @return boolean
     */
    public function verify($value)
    {
        return $value == $this->getMathResult();
    }

    /**
     * Reset the math operators to regenerate a new question.
     *
     * @return void
     */
    public function reset()
    {
        $this->session->forget('mathcaptcha.first');
        $this->session->forget('mathcaptcha.second');
        $this->session->forget('mathcaptcha.operand');
    }

    /**
     * Operand to be used ('*','-','+')
     *
     * @return character
     */
    protected function getMathOperand()
    {
        if (!$this->session->get('mathcaptcha.operand')) {
            $this->session->put(
                'mathcaptcha.operand',
                Arr::random(config('math-captcha.operands'))
            );
        }

        return $this->session->get('mathcaptcha.operand');
    }

    /**
     * The first math operand.
     *
     * @return integer
     */
    protected function getMathFirstOperator()
    {
        if (!$this->session->get('mathcaptcha.first')) {
            $this->session->put(
                'mathcaptcha.first',
                rand(config('math-captcha.rand-min'), config('math-captcha.rand-max'))
            );
        }

        return $this->session->get('mathcaptcha.first');
    }

    /**
     * The second math operand
     * @return integer
     */
    protected function getMathSecondOperator()
    {
        if (!$this->session->get('mathcaptcha.second')) {
            $this->session->put(
                'mathcaptcha.second',
                $this->getMathFirstOperator() + rand(config('math-captcha.rand-min'), config('math-captcha.rand-max'))
            );
        }

        return $this->session->get('mathcaptcha.second');
    }

    /**
     * The math result to be validated.
     * @return integer
     */
    protected function getMathResult()
    {
        switch ($this->getMathOperand()) {
            case '+':
                return $this->getMathFirstOperator() + $this->getMathSecondOperator();
            case '*':
                return $this->getMathFirstOperator() * $this->getMathSecondOperator();
            case '-':
                return abs($this->getMathFirstOperator() - $this->getMathSecondOperator());
            default:
                throw new \Exception('Math captcha uses an unknown operand.');
        }
    }

    /**
     * Build HTML attributes.
     *
     * @param array $attributes
     *
     * @return string
     */
    protected function buildAttributes(array $attributes)
    {
        $html = [];
        foreach ($attributes as $key => $value) {
            $html[] = $key . '="' . $value . '"';
        }
        return count($html) ? ' ' . implode(' ', $html) : '';
    }

}
