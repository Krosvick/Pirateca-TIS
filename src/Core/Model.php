<?php 

namespace Core;

/**
 * Abstract class Model
 *
 * This abstract class serves as the base class for all models in the application.
 * It provides common functionality and properties that are shared among models.
 */
abstract class Model{

    const RULE_REQUIRED = 'required';
    const RULE_EMAIL = 'email';
    const RULE_MIN = 'min';
    const RULE_MAX = 'max';
    const RULE_MATCH = 'match';
    const RULE_PASSWORD_MATCH = 'password_match';
    const RULE_UNIQUE = 'unique';

    public array $errors = [];
    public array $DAOs;

    /**
     *
     * @return array The rules for the Model.
     */
    public function rules(){
        return [] ;
    }

    /**
     *
     * @return array The empty array.
     */
    public function attributes(){
        return [];
    }

    public function validate(){
        foreach($this->rules() as $attribute => $rules){
            $value = $this->{$attribute} ?? $this->{"get_$attribute"}();
            foreach($rules as $rule){
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorByRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorByRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
                    $this->addErrorByRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
                    $this->addErrorByRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
                    $this->addErrorByRule($attribute, self::RULE_MATCH, $rule);
                }
                if($ruleName === self::RULE_PASSWORD_MATCH && !password_verify($this->{$rules['password_match']}, $value)){
                    $this->addErrorByRule($attribute, self::RULE_PASSWORD_MATCH, $rules);
                }
                if($ruleName === self::RULE_UNIQUE){
                    $uniqueAttribute = $rule['attribute'] ?? $attribute;
                    $DAO = $this->DAOs['tableDAO'];
                    $record = $DAO->matchAttribute($uniqueAttribute, $value);
                    if ($record) {
                        $this->addErrorByRule($attribute, self::RULE_UNIQUE, ['field' => $attribute]);
                    }
                }
            }
        }
        return empty($this->errors);
    }

    /**
     * Adds an error message to the `errors` array based on a given attribute, rule, and optional parameters.
     *
     * @param string $attribute The attribute name for which the error message is being added.
     * @param string $rule The rule name for which the error message is being added.
     * @param array $params Optional parameters that can be used to replace placeholders in the error message.
     * @return void
     */
    public function addErrorByRule(string $attribute, string $rule, $params = []) {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach($params as $key => $value){
            $message = str_replace("{{$key}}", $value, $message);
        }
        $this->errors[$attribute][] = $message;
    }
    
     /**
      * Returns an array of error messages based on predefined rules.
      *
      * @return array The array of error messages. Each error message is associated with a specific rule defined in the `Model` class.
      */
     public function errorMessages()
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'This field must be valid email address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must be the same as {match}',
            self::RULE_PASSWORD_MATCH => 'Password must match',
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

    /**
     * Checks if there are any errors associated with a specific attribute.
     *
     * @param string $attribute The name of the attribute to check for errors.
     * @return string|false Returns the error message associated with the attribute if there is an error, otherwise returns false.
     */
    public function hasError($attribute){
        return $this->errors[$attribute] ?? false;
    }
    /**
     * Checks if there are any errors associated with the model.
     *
     * @return bool Returns a boolean value indicating whether there are errors associated with the model or not.
     *              Returns true if there are errors, false if there are no errors.
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }

    /**
     * Returns the errors associated with the model.
     *
     * @return array The errors associated with the model.
     */
    public function getErrors(){
        return $this->errors;
    }

    /**
     * Retrieves the first error message associated with a specific attribute from the errors array.
     *
     * @param string $attribute The name of the attribute to retrieve the error message for.
     * @return string|false The first error message associated with the specified attribute, or false if there are no error messages.
     */
    public function getFirstError($attribute){
        return $this->errors[$attribute][0] ?? false;
    }

    /**
     * Loads data into the properties of the current object.
     *
     * @param object $data The data object containing the properties and their values to be loaded into the current object.
     * @return void
     */
    public function loadData(object $data){
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
                $this->{"set_$key"}($value);
            }
            else{
                $this->{$key} = $value;
            }
        }
    }

    abstract public static function primaryKey();
}