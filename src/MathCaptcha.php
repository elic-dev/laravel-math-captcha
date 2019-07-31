<?php

namespace ElicDev\MathCaptcha;

use Illuminate\Session\SessionManager;

class MathCaptcha
{
    /**
     * @var SessionManager
     */
    private $session;
    
    private $operandsArray = array();
    
    /**
     *
     * @param SessionManager|null $session
     */
    public function __construct(SessionManager $session = null)
    {
        $this->session = $session;
        
        $this->operandsArray = array('+', '*', '-');
    }

    /**
     * Returns the math question as string.
     *
     * @return string
     */
    public function label()
    {
        $operand = $this->getMathOperand();
        
        if($this->getMathFirstOperator() > $this->getMathSecondOperator())
        return sprintf("%d $operand %d", $this->getMathFirstOperator(), $this->getMathSecondOperator());
        else
        return sprintf("%d $operand %d", $this->getMathSecondOperator(), $this->getMathFirstOperator());
    }

    /**
     * Returns the math input field
     * @param  array  $attributes Additional HTML attributes
     * @return string the input field
     */
    public function input(array $attributes = [])
    {
        $attributes['type'] = 'text';
        $attributes['id'] = 'mathcaptcha';
        $attributes['name'] = 'mathcaptcha';
        $attributes['required'] = 'required';
        $attributes['value'] = old('mathcaptcha');

        $html = '<input ' . $this->buildAttributes($attributes) . ' />';

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
            $this->session->put('mathcaptcha.operand', $this->operandsArray[rand(0, 2)]);
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
            $this->session->put('mathcaptcha.first', rand(2, 7));
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
            $this->session->put('mathcaptcha.second', rand(5, 11));
        }

        return $this->session->get('mathcaptcha.second');
    }

    /**
     * The math result to be validated.
     * @return integer
     */
    protected function getMathResult()
    {
        switch ($this->getMathOperand())
        {
            case '+' : return $this->getMathFirstOperator() + $this->getMathSecondOperator(); break;
            case '*' : return $this->getMathFirstOperator() * $this->getMathSecondOperator(); break;
            case '-' :
                if($this->getMathFirstOperator() > $this->getMathSecondOperator())
                return $this->getMathFirstOperator() - $this->getMathSecondOperator();
                else
                return $this->getMathSecondOperator() - $this->getMathFirstOperator();
                break;
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
