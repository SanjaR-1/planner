<?php
namespace App\Exceptions;
use Exception;

class BusinessException extends Exception{
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Xatolik yuz berdi', 400);
    }
}
