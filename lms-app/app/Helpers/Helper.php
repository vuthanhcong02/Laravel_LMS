<?php

if (! function_exists('splitName')) {
    function splitName(string $fullName): array
    {
        $nameParts = explode(' ', trim($fullName));
        $firstName = array_shift($nameParts);
        $lastName = implode(' ', $nameParts);

        return [$firstName, $lastName];
    }
}
