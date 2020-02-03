<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Log\Logger;
use Twilio\Rest\Client;

class CombatController extends Controller
{
    public function __invoke(string $id, Logger $logger): JsonResponse
    {
        $combat = $this->makeCombat($logger);
        return response()->json(
            $combat->toArray()
        );
    }
}
