<?php

if (!function_exists('recaptcha_keys')) {
    function recaptcha_keys(): array
    {
        if (ENVIRONMENT === 'production') {
            return [
                'site'   => getenv('RECAPTCHA_SITE_KEY_PROD'),
                'secret' => getenv('RECAPTCHA_SECRET_KEY_PROD'),
            ];
        }

        return [
            'site'   => getenv('RECAPTCHA_SITE_KEY_LOCAL'),
            'secret' => getenv('RECAPTCHA_SECRET_KEY_LOCAL'),
        ];
    }
}
