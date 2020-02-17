<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\SyncGrant;

class TokenController extends Controller
{
    public function __invoke(): JsonResponse
    {

        $twilioAccountSid = getenv('TWILIO_SID');
        $twilioApiKey =  getenv('TWILIO_API_TOKEN');
        $twilioApiSecret = getenv('TWILIO_API_SECRET');

        $token = new AccessToken(
            $twilioAccountSid,
            $twilioApiKey,
            $twilioApiSecret,
            3600,
            'Chatemon',
        );

        // Grant access to Sync
        $syncGrant = new SyncGrant();
        if (empty(getenv('TWILIO_SYNC_SID'))) {
            $syncGrant->setServiceSid('default');
        } else {
            $syncGrant->setServiceSid(getenv('TWILIO_SYNC_SID'));
        }
        $token->addGrant($syncGrant);


        // return serialized token and the user's randomly generated ID
        return response()->json([
            'identity' => 'Chatemon',
            'token' => $token->toJWT(),
        ]);
    }
}
