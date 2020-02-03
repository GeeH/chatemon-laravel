<?php

namespace App\Http\Controllers;

use Illuminate\Log\Logger;
use Twilio\Rest\Client;

class SyncController extends Controller
{
    const DOCUMENT = 'STATIC';

    public function __invoke(Logger $logger)
    {
        $accountId = getenv('TWILIO_SID');
        $authToken = getenv('TWILIO_ACCOUNT_TOKEN');
        $syncSid = getenv('TWILIO_SYNC_SID');

        $client = new Client($accountId, $authToken);

//        $document = $client->sync->v1->services($syncSid)
//            ->documents
//            ->read()[0];
//


        foreach (
            $client->sync->v1->services($syncSid)
                ->documents
                ->read() as $document
        ) {
            $document->delete();
        }

        $document = $client->sync->v1->services($syncSid)
            ->documents
            ->create(['data' => $this->makeCombat($logger)->toArray()]);

        var_dump($document);
    }
}
