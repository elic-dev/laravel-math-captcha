<?php 

namespace ElicDev\MathCaptcha;

use ElicDev\MathCaptcha\MathCaptcha;
use Illuminate\Session\SessionManager;

class MathematicsCaptcha extends MathCaptcha
{
    private $session;
    private $operandsArray = array();
    
    public function __construct(SessionManager $session = null)
    {
        $this->session = $session;
        parent::__construct($this->session);
        
        $this->operandsArray = array('+', '*', '-');
    }
    
    protected function getMathOperand()
    {
        if (!$this->session->get('mathcaptcha.operand')) {
            $this->session->put('mathcaptcha.operand', $this->operandsArray[rand(0, 2)]);
        }
        
        return $this->session->get('mathcaptcha.operand');
    }
    
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
    
    public function label()
    {
        $operand = $this->getMathOperand();
        
        if($this->getMathFirstOperator() > $this->getMathSecondOperator())        
        return sprintf("%d $operand %d", $this->getMathFirstOperator(), $this->getMathSecondOperator());
        else 
        return sprintf("%d $operand %d", $this->getMathSecondOperator(), $this->getMathFirstOperator());
    }
    
    public function result()
    {
        return  sprintf("%d", $this->getMathResult());
    }
    
}