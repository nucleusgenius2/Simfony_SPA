<?php


namespace App\Traits;


trait ResponseController
{

    public int $code = 422;

    public string $status = 'error';

    public mixed $text = null;

    public mixed $messagesErrors = '';

    public mixed $json = null;

}
