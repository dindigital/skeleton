<?php

namespace Site\Models\DataAccess\Entity\Exception;

class EntityValidationException extends \Exception
{

    protected $errors;

    public function __construct($errors) {
        $this->errors = $errors;
    }

    public function getErrors() {
        return $this->errors;
    }

}
