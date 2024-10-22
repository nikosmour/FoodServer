<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Saml2;

class Saml2Controller extends Controller
{
    public function acs(Request $request)
    {
        $samlUser = Saml2::getSaml2User();
        $userData = $samlUser->getAttributes();

        // Example: Get email and status
        $email = $userData['email'][0];
        $status = $userData['status'][0];

        return response()->json([
            'email' => $email,
            'status' => $status
        ]);
    }
}
