<?php

namespace App\Http\Controllers;

use Chatemon\Combat;
use Chatemon\CombatState;
use Chatemon\Factory\CombatantFactory;
use Chatemon\Randomizer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Twilio\Rest\Client;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function makeCombat(LoggerInterface $logger): Combat
    {
        return new Combat(
            CombatantFactory::create(
                [
                    'name' => 'Developer', 'level' => 13, 'attack' => 142, 'defence' => 20,
                    'health' => 50, 'maxHealth' => 50, 'moves' => [],
                    'speed' => 5,
                    'id' => Uuid::uuid4()->toString()
                ]
            ),
            CombatantFactory::create(
                ['name' => 'HR Executive', 'level' => 21, 'attack' => 130, 'defence' => 23,
                    'health' => 47, 'maxHealth' => 47, 'moves' => [],
                    'speed' => 1,
                    'id' => Uuid::uuid4()->toString()]
            ),
            CombatState::fresh(),
            new Randomizer(),
            $logger
        );
    }

    public function getCombat(): array
    {
        $accountId = getenv('TWILIO_SID');
        $authToken = getenv('TWILIO_ACCOUNT_TOKEN');
        $syncSid = getenv('TWILIO_SYNC_SID');

        $client = new Client($accountId, $authToken);
        $document = $client->sync->v1->services($syncSid)
            ->documents
            ->read()[0];

        return $document->data;
    }
}
