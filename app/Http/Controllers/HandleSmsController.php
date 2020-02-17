<?php

namespace App\Http\Controllers;

use Chatemon\Exception\CombatAlreadyWonException;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Twilio\TwiML\MessagingResponse;

class HandleSmsController extends Controller
{
    private $moveIndex = [
        'A' => 0,
        'B' => 1,
        'C' => 2
    ];

    public function __invoke(Request $request, Logger $logger): string
    {
        $messageResponse = new MessagingResponse();
        if (strlen($request->post('Body')) !== 1) {
            $messageResponse->message('Please only send the move letter you wish to play, e.g. "A"');
            return (string)$messageResponse;
        }

        $combat = $this->getCombat($logger);
        $moveIndex = ucfirst($request->post('Body'));

        try {
            $combat->takeTurn($this->moveIndex[$moveIndex], 0);
            $this->saveCombat($combat);
            $messageResponse->message("Thanks, You've played move {$moveIndex}");
            return (string)$messageResponse;
        } catch (CombatAlreadyWonException $e) {
            $messageResponse->message('Sorry, you\'re too late, this game is already over!');
            return (string)$messageResponse;
        } catch (\Exception $e) {
            $messageResponse->message($moveIndex . ' is not a valid, valid moves are A, B or C');
            return (string)$messageResponse;
        }
    }
}

// Play A -> Please only send the move letter you wish to play, e.g. "A"
// D -> D is not a valid, valid moves are A, B or C
// B -> Thanks, you've played move "HR Message"
