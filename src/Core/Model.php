<?php 

namespace Core;

abstract class Model{

    const RULE_REQUIRED = 'required';
    const RULE_EMAIL = 'email';
    const RULE_MIN = 'min';
    const RULE_MAX = 'max';
    const RULE_MATCH = 'match';
    const RULE_UNIQUE = 'unique';

    public array $errors = [];
    public array $DAOs;

    public function rules(){
        return [] ;
    }

    public function attributes(){
        return [];
    }

    public function validate(){
        foreach($this->rules() as $attribute => $rules){
            $value = $this->{"get_$attribute"}();
            foreach($rules as $rule){
                $ruleName = $rule;
                if(!is_string($ruleName)){
                    $ruleName = $rule[0];
                }
                if($ruleName === self::RULE_REQUIRED && !$value){
                    $this->addErrorByRule($attribute, self::RULE_REQUIRED);
                }
                if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $this->addErrorByRule($attribute, self::RULE_EMAIL);
                }
                if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']){
                    $this->addErrorByRule($attribute, self::RULE_MIN, $rule);
                }
                if($ruleName === self::RULE_MAX && strlen($value) > $rule['max']){
                    $this->addErrorByRule($attribute, self::RULE_MAX, $rule);
                }
                if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}){
                    $this->addErrorByRule($attribute, self::RULE_MATCH, $rule);
                }
                if($ruleName === self::RULE_UNIQUE){
                    $uniqueAttribute = $rule['attribute'] ?? $attribute;
                    $DAO = $this->DAOs['tableDAO'];
                    $record = $DAO->matchAttribute($uniqueAttribute, $value);
                    if($record){
                        $this->addErrorByRule($attribute, self::RULE_UNIQUE, ['field' => $attribute]);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    public function addErrorByRule(string $attribute, string $rule, $params = []){
        $message = $this->errorMessages()[$rule] ?? '';
        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }
    
     public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_UNIQUE => 'Record with with this {field} already exists',
        ];
    }

    public function errorMessage($rule){
        return $this->errorMessages()[$rule];
    }

    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute][] = $message;
    }

    public function hasError($attribute){
        return $this->errors[$attribute] ?? false;
    }

    public function hasErrors(){
        return !empty($this->errors);
    }

    public function getErrors(){
        return $this->errors;
    }

    public function getFirstError($attribute){
        return $this->errors[$attribute][0] ?? false;
    }

    public function loadData(object $data){
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
                $this->{"set_$key"}($value);
            }
        }
    }

    abstract public static function primaryKey();
}