<?php declare(strict_types=1);

namespace Tests\Functional;

use App\Http\Controllers\HandleSmsController;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Mockery;
use PHPUnit\Framework\TestCase;
use Twilio\Rest\Client;

class HandleSmsControllerTest extends TestCase
{
    /** @var Mockery\Mock|Request */
    private $request;

    /** @var Logger */
    private $logger;

    /** @var \PHPUnit\Framework\MockObject\MockObject|Client */
    private $client;

    private HandleSmsController $controller;

    public function setUp(): void
    {
        $this->request = Mockery::mock(Request::class);

        $psrLogger = new \Symfony\Component\HttpKernel\Log\Logger();
        $logger = new Logger($psrLogger);
        $this->logger = $logger;

        $twilioAccountSid = getenv('TWILIO_SID');
        $twilioAccountToken = getenv('TWILIO_ACCOUNT_TOKEN');
        $this->client = new Client($twilioAccountSid, $twilioAccountToken);

        $this->controller = new HandleSmsController($this->client);
    }

    public function testInvokeReturnsInvalidMoveResponseWhenNoMoveIsSent()
    {
        $this->request
            ->shouldReceive('post')
            ->with('Body')
            ->once()
            ->andReturn('');

        $response = $this->controller->__invoke($this->request, $this->logger);

        self::assertStringIsTwiml($response);
        self::assertStringContainsString('Please only send the move letter you wish to play, e.g. "A"', $response);
    }

    public function testInvokeReturnNotAMoveResponseWhenWrongCharacterIsSent()
    {
        $this->request
            ->shouldReceive('post')
            ->andReturnUsing(function ($argument) {
                if ($argument === 'Body') {
                    return 'D';
                }
                if ($argument === 'From') {
                    return '01234567890';
                }
                return 'GB';
            });

        $response = $this->controller->__invoke($this->request, $this->logger);

        self::assertStringIsTwiml($response);
    }

    private static function assertStringIsTwiml(string $response): void
    {
        self::assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', $response);
    }
}
