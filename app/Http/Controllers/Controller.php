<?php

namespace App\Http\Controllers;

use Chatemon\Combat;
use Chatemon\CombatState;
use Chatemon\Factory\CombatantFactory;
use Chatemon\Randomizer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Log\Logger;
use Illuminate\Routing\Controller as BaseController;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Twilio\Rest\Client;
use Twilio\Rest\Sync\V1\Service\DocumentInstance;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private Client $client;

    public function __construct()
    {
        $twilioAccountSid = getenv('TWILIO_SID');
        $twilioAccountToken = getenv('TWILIO_ACCOUNT_TOKEN');
        $this->client = new Client($twilioAccountSid, $twilioAccountToken);
    }

    protected function makeCombat(LoggerInterface $logger): Combat
    {
        $enemyNames = [
            'Off By One' => 30,
            'Divide By Zero' => 10,
            'Syntax Error' => 23,
            'Invalid Argument' => 5,
            'Unexpected Paamayim Nekudotayim' => 100,
            'Race Condition' => 18,
        ];

        $enemyName = array_rand($enemyNames);
        $enemyLevel = $enemyNames[$enemyName];

        return new Combat(
            CombatantFactory::fromArray(
                [
                    'name' => 'Developer', 'level' => 15, 'attack' => 150, 'defence' => 20,
                    'health' => 50, 'maxHealth' => 50, 'moves' => [],
                    'speed' => 5,
                    'id' => Uuid::uuid4()->toString()
                ]
            ),
            CombatantFactory::fromArray(
                ['name' => $enemyName, 'level' => $enemyLevel, 'attack' => 130, 'defence' => 23,
                    'health' => 47, 'maxHealth' => 47, 'moves' => [],
                    'speed' => 1,
                    'id' => Uuid::uuid4()->toString()]
            ),
            CombatState::fresh(),
            new Randomizer(),
            $logger
        );
    }

    protected function getCombat(LoggerInterface $logger): Combat
    {
        $document = $this->getCombatDocument();
        // $data is an array
        $data = $document->data;

        return new Combat(
            CombatantFactory::fromArray($data['combatantOne']),
            CombatantFactory::fromArray($data['combatantTwo']),
            CombatState::fromArray(['turns' => $data['turns'], 'winner' => $data['winner']]),
            new Randomizer(),
            $logger
        );
    }

    protected function saveCombat(Combat $combat, string $country = null, string $number = null): void
    {
        $document = $this->getCombatDocument();
        $data = $combat->toArray();

        if ($country) {
            $data['fromCountry'] = $country;
        }
        if ($number) {
            $data['fromNumber'] = $number;
        }

        $document->update(['data' => $data]);
    }

    protected function startNewCombat(LoggerInterface $logger): void
    {
        $combat = $this->makeCombat($logger);
        $combat->getCombatantTwo()->moves[0]->name = 'Unexpected Meeting';
        $this->saveCombat($combat);
    }

    protected function getCombatDocument(): DocumentInstance
    {
        $syncSid = getenv('TWILIO_SYNC_SID');
        return $this->client->sync->v1->services($syncSid)
            ->documents
            ->read()[0];
    }
}
