<?php

namespace App\Support;

use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Throwable;

class DuplicateEntryGuard
{
    /**
     * Two near-simultaneous requests can both pass Laravel's uniqueness
     * validation before either row is written; the database's unique index
     * is the real backstop. This translates that specific violation
     * (MySQL error 1062) into a normal validation error on the given field
     * instead of letting it surface as an unhandled 500.
     */
    public static function translate(QueryException $exception, string $field, string $message): Throwable
    {
        $isDuplicateEntry = ($exception->errorInfo[1] ?? null) === 1062;

        return $isDuplicateEntry
            ? ValidationException::withMessages([$field => $message])
            : $exception;
    }
}
