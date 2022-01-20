<?php


use Illuminate\Validation\Factory;

$validator->extend(
    'upload-image-limit',
    function ($attribute, $value, $parameters)
    {
        $count = count(array_get($this->file, $parameters));
        if($count > 2) return false;
        return true;
    }
);

$validator->extend(
    'phone_number',
    function ($attribute, $value, $parameters)
    {
        return strlen(preg_replace('#^.*([0-9]{3})[^0-9]*([0-9]{3})[^0-9]*([0-9]{4})$#', '$1$2$3', $value)) == 10;
    }
);
