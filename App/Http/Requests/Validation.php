<?php

namespace App\Http\Requests;

use App\Database\Models\Model;

class Validation {

    private $inputValue;
    private string $inputValueName;
    private array $errors = [];
    private array $oldValues = [];

    /**
     * Get the value of inputValue
     */ 
    public function getInputValue()
    {
        return $this->inputValue;
    }

    /**
     * Set the value of inputValue
     *
     * @return  self
     */ 
    public function setInputValue($inputValue)
    {
        $this->inputValue = $inputValue;
        return $this;
    }

    /**
     * Set the value of inputValueName
     *
     * @return  self
     */ 
    public function setInputValueName($inputValueName)
    {
        $this->inputValueName = $inputValueName;

        return $this;
    }

    /**
     * Get the value of errors
     */ 
    public function getErrors()
    {
        return $this->errors;
    }

        /**
     * Get the value of oldValues
     */ 
    public function getOldValues()
    {
        return $this->oldValues;
    }

    /**
     * Set the value of oldValues
     *
     * @return  self
     */ 
    public function setOldValues($oldValues)
    {
        $this->oldValues = $oldValues;

        return $this;
    }

    public function getOldValue($inputName) :?string
    {
        if(isset($this->oldValues[$inputName])){
            return $this->oldValues[$inputName];
        }
        return null;
    }

    public function string() :self
    { 
        if(is_numeric($this->inputValue)){
            $this->errors[$this->inputValueName][__FUNCTION__] = "{$this->inputValueName} must be string" ;
        }
        // $specialChars = ['@','^','#','.','!','$','%','&','/','*','(',')','-','+','=','`',',','<','>','?','~',"'",'"',"ـ",'}','{','‘'];
        return $this;
    }

    public function required() :self
    {
       if(trim($this->inputValue) == "" || $this->inputValue == null){
            $this->errors[$this->inputValueName][__FUNCTION__] = "{$this->inputValueName} is required";
       }
       
       return $this;
    }

    public function between(int $min,int $max) :self
    {
        if(strlen($this->inputValue) < $min || strlen($this->inputValue) > $max){
            $this->errors[$this->inputValueName][__FUNCTION__] = "{$this->inputValueName} Must Be Between {$min} , {$max}";
        }
        
        return $this;
    }

    public function digits(int $digits) :self
    {
        if(strlen($this->inputValue) != $digits ){
            $this->errors[$this->inputValueName][__FUNCTION__] = "{$this->inputValueName} Must Be {$digits} digits";
        }
        
        return $this;
    }


    public function regex(string $pattern, $message = null) :self
    {
        if(! preg_match($pattern,$this->inputValue)){
            $this->errors[$this->inputValueName][__FUNCTION__] = $message ?? "{$this->inputValueName} Invalid";
        }
        return $this;
    }

    public function in(array $values) :self 
    {
        if(! in_array($this->inputValue,$values)){
            $this->errors[$this->inputValueName][__FUNCTION__] = "{$this->inputValueName} must be " . implode(', ',$values);
        }
        return $this;
    }

    public function confirmed($confirmationValue) :self
    {
        if($this->inputValue != $confirmationValue){
            $this->errors[$this->inputValueName][__FUNCTION__] = "{$this->inputValueName} doesn't match";
        }
        return $this;
    }

    public function unique(string $tableName ,string  $columnName)  :self
    {
        $Model = new Model;
        $result = $Model->search($tableName,$columnName,$this->inputValue);
        if($result->num_rows == 1){
            $this->errors[$this->inputValueName][__FUNCTION__] = "{$this->inputValueName} already exists";
        }
        return $this;
    }

    public function exists(string $tableName ,string  $columnName) :self
    {
        $Model = new Model;
        $result = $Model->search($tableName,$columnName,$this->inputValue);
        if($result->num_rows == 0){
            $this->errors[$this->inputValueName][__FUNCTION__] = "{$this->inputValueName} not exist";
        }
        return $this;
    }


    public function getError($inputName) :?string
    {
        if(isset($this->errors[$inputName])){
            foreach($this->errors[$inputName] AS $error){
                return $error;
            } 
        }
        return null;
    }

    public function getMessage($error)
    {
       return "<p class='text-danger font-weight-bold'> ".ucfirst($this->getError($error))." </p>";
    }

}