<?php

if (! function_exists('splitName')) {
    function splitName(string $fullName): array
    {
        $nameParts = explode(' ', trim($fullName));
        $firstName = array_shift($nameParts);
        $lastName = implode(' ', $nameParts);

        return [
            'first_name' => $firstName ?? '',
            'last_name'  => $lastName ?? '',
        ];;
    }
}