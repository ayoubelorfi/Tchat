<?php

namespace Core;

class Security
{
    public function CryptJWT(array $args = []): string
    {
        if(!isset($args['secret_key']))
            return 'Merci de spécifier le "secret_key"';
        if(!isset($args['secret_iv']))
            return 'Merci de spécifier le "secret_iv"';
        if(!isset($args['text']))
            return 'Merci de spécifier le "text"';
        
        $encrypt_method = "AES-256-CBC";
        $secret_key = $args['secret_key'];
        $secret_iv = $args['secret_iv'];
        
        $key = substr(hash('sha256', $secret_key), 0, 32);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $output = openssl_encrypt($args['text'], $encrypt_method, $key, 0, $iv);

        return base64_encode($output);
    }

    public function DecryptJWT(array $args = []): string
    {
        if(!isset($args['secret_key']))
            return 'Merci de spécifier le "secret_key"';
        if(!isset($args['secret_iv']))
            return 'Merci de spécifier le "secret_iv"';
        if(!isset($args['text']))
            return 'Merci de spécifier le "text"';
        
        $encrypt_method = "AES-256-CBC";
        $secret_key = $args['secret_key'];
        $secret_iv = $args['secret_iv'];
        
        $key = substr(hash('sha256', $secret_key), 0, 32);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        return openssl_decrypt(base64_decode($args['text']), $encrypt_method, $key, 0, $iv);

    }

}
