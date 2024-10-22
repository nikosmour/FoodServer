<?php

namespace App\Http\Controllers;

use Aacotroneo\Saml2\Http\Controllers\Saml2Controller;
use Aacotroneo\Saml2\Saml2Auth;

class CustomSaml2Controller extends Saml2Controller
{
    public function metadata(Saml2Auth $saml2Auth): \Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
    {
        // This line is from the parent method
        $metadata = $saml2Auth->getMetadata();

        /*// Modify the metadata response to set validUntil and cacheDuration
        $validUntil = now()->addDays(10)->toIso8601String();
        $metadata = preg_replace('/validUntil="[^"]*"/', 'validUntil="' . $validUntil . '"', $metadata);
        // You can also modify cacheDuration if necessary
        $cacheDuration = strtotime($validUntil) - time();
        $metadata = preg_replace('/cacheDuration="[^"]*"/', 'cacheDuration="PT'.$cacheDuration .'S"', $metadata);*/

        return response($metadata, 200)->header('Content-Type', 'application/xml');
    }
}
