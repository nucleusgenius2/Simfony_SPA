<?php

namespace App\Service;

class ValidationError
{
    public static function getMessageError($errors): ?array
    {
        $messagesErrors = [];

        foreach ($errors as $message) {
            $messagesErrors[] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        return count($messagesErrors) > 0 ? $messagesErrors : null;
    }
}
