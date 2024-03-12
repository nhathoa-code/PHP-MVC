<?php 

namespace Mvc\Core;

class Validator {
    
    protected $data;
    protected $errors = [];

    public function __construct($data) {
        $this->data = $data;
    }

    public function validate($field,$methods,$stop_on_first = false)
    {
        foreach($methods as $index => $item){
            $is_validated = null;
            if(is_string($index)){
                if(strpos($index,":") || strpos($index,"|")){
                    $validation = strpos($index,"|") ? explode("|",$index) : explode(":",$index);                
                    $method = $validation[0];
                    $constraint = $validation[1]; 
                }else{
                    $method = $index;
                }
                if(isset($constraint)){
                    $is_validated = call_user_func([$this,$method],$field,$item,$constraint);
                }else{
                    $is_validated = call_user_func([$this,$method],$field,$item);
                }     
            }else{
                if(strpos($item,":") || strpos($index,"|")){
                    $validation = strpos($item,"|") ? explode("|",$item) : explode(":",$item);                
                    $method = $validation[0];
                    $constraint = $validation[1]; 
                }else{
                    $method = $item;
                }
                if(isset($constraint)){
                    $is_validated = call_user_func([$this,$method],$field,null,$constraint);
                }else{
                    $is_validated = call_user_func([$this,$method],$field);
                }          
            }
            if(!$is_validated && $stop_on_first){
                break;
            }
        }
    }

    protected function required($field, $message = 'This field is required.') {
        $value = $this->getValue($field);
        if ($value === null || ($this->isFile($field) ? (is_array($_FILES[$field]['tmp_name']) ? !is_uploaded_file($_FILES[$field]['tmp_name'][0]) : !is_uploaded_file($_FILES[$field]['tmp_name'])) : $value === "")) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    protected function email($field, $message = 'Invalid email format.') {
        $value = $this->getValue($field);
        if ($value === null || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    protected function max($field, $message = null, $constraint) {
        $value = $this->getValue($field);
        $constraint = intval($constraint);
        if ($value === null || strlen($value) > $constraint) {
            $this->addError($field, $message ? $message : "Maximum length of this field is {$constraint}");
            return false;
        }
        return true;
    }

    protected function min($field, $message = null, $constraint) {
        $constraint = intval($constraint);
        $value = $this->getValue($field);
        if ($value === null || strlen($value) < $constraint) {
            $this->addError($field, $message ? $message : "Minimum length of this field is {$constraint}");
            return false;
        }
        return true;
    }

    protected function match($field, $message = null, $constraint) {
        $value = $this->getValue($field);
        if ($value === null || $value !== $this->getValue($constraint)) {
            $this->addError($field, $message ? $message : "This field must match {$constraint} field");
            return false;
        }
        return true;
    }

    protected function pattern($field, $message = null, $constraint) {
        $value = $this->getValue($field);
        if ($value === null || !preg_match($constraint,$value)) {
            $this->addError($field, $message ? $message : "This field must follow the pattern");
            return false;
        }
        return true;
    }

    protected function number($field, $message = 'This field must be a number') {
        $value = $this->getValue($field);
        if ($value === null || !is_numeric($value)) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    protected function integer($field, $message = 'This field must be an integer') {
        $value = $this->getValue($field);
        if ($value === null || (!is_numeric($value) && !is_int($value + 0))) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    protected function greaterThan($field, $message = null, $constraint) {
        $compare = null;
        if(!is_numeric($constraint) && is_string($constraint)){
            $compare = $this->getValue($field) <= $this->getValue($constraint);
        }else if(is_numeric($constraint)){
            $compare = floatval($this->getValue($field)) <= floatval($constraint);
        }
        if ($compare) {
            $this->addError($field, $message ? $message : "This field must be greater than {$constraint}" . ($this->getValue($constraint) ? " field" : ""));
            return false;
        }
        return true;
    }

    protected function lessThan($field, $message = null, $constraint) {
        $compare = null;
        if(!is_numeric($constraint) && is_string($constraint)){
            $compare = $this->getValue($field) >= $this->getValue($constraint);
        }else if(is_numeric($constraint)){
            $compare = floatval($this->getValue($field)) >= floatval($constraint);
        }
        if ($compare) {
            $this->addError($field, $message ? $message : "This field must be less than {$constraint}" . ($this->getValue($constraint) ? " field" : ""));
            return false;
        }
        return true;
    }

    protected function array($field,$message = "This field must be an array"){
        $value = $this->getValue($field);
        if($value === null || !is_array($value)){
            $this->addError($field, $message);
            return false;
        }
    }

    protected function unique($field, $message = null, $constraint) {
        $value = $this->getValue($field);
        if(strpos($constraint,",")){
            $table = explode(",",$constraint)[0];
            $column = explode(",",$constraint)[1];
        }else{
            $table = $constraint;
        }
        if(strpos($table,"_exclude")){
            $exclude_id = explode("_exclude-",$table)[1];
            $table = explode("_exclude-",$table)[0];
        }
        $query = DB::table($table);
        if(isset($column)){
            $query->where($column,$value);
        }else{
            $query->where($field,$value);
        }
        if(isset($exclude_id)){
            $query->where("id","!=",$exclude_id);
        }
        $result = $query->first();
        if($result){
            $this->addError($field, $message ? $message : "This value has already taken");
            return false;
        }
        return true;
    }

    protected function values($field, $message = null,$constraint) {
        $value = $this->getValue($field);
        $values = explode(",",$constraint);
        if (!in_array($value,$values)) {
            $this->addError($field, $message === null ? "This field'value must be in " . implode(",",$values) : $message);
            return false;
        }
        return true;
    }

    protected function datetime($field, $message = 'This field must be in format datetime',$constraint = null) {
        $value = $this->getValue($field);
        $format = $constraint === null ? 'd-m-Y H:i:s' : $constraint;
        $dateTimeObject = \DateTime::createFromFormat($format, $value);
        if ($dateTimeObject === false || $dateTimeObject->format($format) !== $value) {
            $this->addError($field, $message);
            return false;
        }
        return true;
    }

    protected function type($field, $message = 'Type is not allowed',$constraint) {
        $value = $this->getValue($field);
        $allowed_types = explode(",",$constraint);
        if(is_array($value['type'])){
            foreach($value['type'] as $type){
                if(!in_array($type,$allowed_types)){
                    $this->addError($field, $message);
                    return false;
                }
            }
        }else{
            if (!in_array($value['type'],$allowed_types)) {
                $this->addError($field, $message);
                return false;
            }
        }
        return true;
    }

    public function getErrors() {
        return $this->errors;
    }

    protected function getValue($field) {
        $keys = explode("=>",$field);
        $ref = &$this->data;
        foreach($keys as $key){
            $ref = &$ref[$key];
        }
        return isset($ref) ? $ref : null;
    }

    protected function isFile($field)
    {
        return isset($_FILES[$field]);
    }

    protected function addError($field, $message) {
        $this->errors[$field][] = $message;
    }

    public function fails()
    {
        return !empty($this->errors);
    }

    public function flashErrors($flush = true)
    {
        $_SESSION['flash_session']['validation_errors'] = $this->errors;
        if($flush){
            $_SESSION['flash_session']['flush'] = true; 
        }
    }

    public function flashInputs()
    {
        $_SESSION['flash_session']['old_inputs'] = $this->data;
    }
}