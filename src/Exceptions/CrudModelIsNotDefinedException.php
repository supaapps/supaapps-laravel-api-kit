<?php

namespace Supaapps\Supalara\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CrudModelIsNotDefinedException extends Exception
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;

    protected $message = 'CRUD model is not defined as controller property';
}
