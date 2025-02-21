<?php

if (!function_exists('encodeId')) {
    function encodeId($id)
    {
        return rtrim(strtr(base64_encode($id), '+/', '-_'), '=');
    }
}

if (!function_exists('decodeId')) {
    function decodeId($encodedId)
    {
        return base64_decode(strtr($encodedId, '-_', '+/'));
    }
}
