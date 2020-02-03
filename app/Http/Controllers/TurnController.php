<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Chatemon\Exception\CombatAlreadyWonException;
use Illuminate\Http\JsonResponse;
use Illuminate\Log\Logger;

class TurnController extends Controller
{
    public function __invoke(string $id, int $moveIndex, Logger $logger): JsonResponse
    {
        $combat = $this->makeCombat($logger);
        $feedback = $combat->takeTurn($moveIndex, 0);

        $return = $combat->toArray();
        $return['feedback'] = $feedback;

        return response()->json(
            $return
        );
    }
}
