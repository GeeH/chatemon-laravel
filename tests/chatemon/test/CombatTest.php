<?php

namespace ChatemonTest;

use Chatemon\Combat;
use Chatemon\CombatState;
use Chatemon\Exception\CombatAlreadyWonException;
use Chatemon\Exception\CombatNotWonException;
use Chatemon\Exception\MoveDoesNotExistException;
use Chatemon\Factory\CombatantFactory;
use Chatemon\Randomizer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class CombatTest extends TestCase
{

    protected Combat $combat;

    public function setUp(): void
    {
        $this->combat = self::getCombat();
    }

    public static function getCombat(
        Randomizer $randomizer = null,
        array $combatantOneData = [],
        array $combatantTwoData = []
    ): Combat
    {
        $handler = new StreamHandler(__DIR__ . '/../log/combat.log', Logger::DEBUG);
        $logger = new Logger('test-logger', [$handler]);

        if (is_null($randomizer)) {
            $randomizer = new Randomizer();
        }

        return new Combat(
            CombatantFactory::fromArray(
                !empty($combatantOneData) ? $combatantOneData :
                    [
                        'name' => 'One', 'level' => 1, 'attack' => 100, 'defence' => 5,
                        'health' => 20, 'maxHealth' => 20, 'moves' => [], 'speed' => 10,
                        'id' => Uuid::uuid4()->toString()
                    ]
            ),
            CombatantFactory::fromArray(
                !empty($combatantTwoData) ? $combatantTwoData :
                    [
                        'name' => 'Two', 'level' => 1, 'attack' => 100, 'defence' => 5,
                        'health' => 12, 'maxHealth' => 12, 'moves' => [], 'speed' => 5,
                        'id' => Uuid::uuid4()->toString()
                    ]
            ),
            CombatState::fresh(),
            $randomizer,
            $logger
        );
    }

    public function testConstructSetsCombatantsAndId()
    {
        self::assertTrue(Uuid::isValid($this->combat->getId()));
    }

    public function testTakingTurnIncrementsTurnCounter()
    {
        $this->combat->takeTurn(0, 0);
        self::assertEquals(1, $this->combat->getTurns());
    }

    public function testRunningCombatTurnsGeneratesWinner()
    {
        while (!$this->combat->isWinner()) {
            $this->combat->takeTurn(0, 0);
        }

        self::assertEquals(2, $this->combat->getTurns());
    }

    public function testRunningCombatTurnWhenWinnerExistsThrowsException()
    {
        while (!$this->combat->isWinner()) {
            $this->combat->takeTurn(0, 0);
        }
        self::expectException(CombatAlreadyWonException::class);
        $this->combat->takeTurn(0, 0);
    }

    public function testGettingWinnerWhenNoWinnerExistsThrowsException()
    {
        self::expectException(CombatNotWonException::class);
        $this->combat->getWinner();
    }

    public function testPlayingTurnWithInvalidMoveThrowsException()
    {
        self::expectException(MoveDoesNotExistException::class);
        $this->combat->takeTurn(100, 0);

        self::expectException(MoveDoesNotExistException::class);
        $this->combat->takeTurn(0, 100);
    }

    public function damageAlgorithmDataProvider()
    {
        return [
            [100, 100, 100, 100, 73, 86],
            [1, 50, 10, 1, 18, 22],
        ];
    }

    /**
     * @dataProvider damageAlgorithmDataProvider
     */
    public function testDamageCalculator(
        int $attackerLevel, int $attackerAttack, int $moveDamage,
        int $defenderDefense, int $minimumDamage, int $maximumDamage
    )
    {
        $damage = $this->combat
            ->calculateDamage($attackerLevel, $attackerAttack, $moveDamage, $defenderDefense);
        self::assertGreaterThanOrEqual($minimumDamage, $damage);
        self::assertLessThanOrEqual($maximumDamage, $damage);
    }

    public function testMoveMissesWithMaximumDiceRoll()
    {
        $randomizerMock = self::getMockBuilder(Randomizer::class)
            ->getMock();

        $randomizerMock->expects($this->exactly(2))
            ->method('__invoke')
            ->with(1, 100)
            ->willReturn(100);

        $combat = $this->getCombat($randomizerMock);
        $combat->takeTurn(2, 2);

        self::assertEquals(20, $combat->getCombatantOne()->health);
        self::assertEquals(12, $combat->getCombatantTwo()->health);
    }

    public function testToArrayConvertsToReadableArray()
    {
        $combatArray = $this->combat->toArray();
        $fields = ['combatantOne', 'combatantTwo', 'turns', 'id', 'winner', 'feedback'];
        foreach ($fields as $field) {
            self::assertArrayHasKey($field, $combatArray);
        }
        self::assertArrayHasKey('moves', $combatArray['combatantOne']);
        self::assertIsArray($combatArray['combatantOne']['moves'][0]);

        self::assertArrayHasKey('moves', $combatArray['combatantTwo']);
        self::assertIsArray($combatArray['combatantTwo']['moves'][0]);
    }

    public function testCombatantWithHigherSpeedGoesFirst()
    {
        $combat = $this->getCombat(null,
            [
                'level' => 1,
                'attack' => 1,
                'defence' => 1,
                'health' => 1,
                'maxHealth' => 1,
                'name' => 'One',
                'speed' => 1,
                'id' => Uuid::uuid4()->toString(),
                'moves' => [],
            ],
            [
                'level' => 100,
                'attack' => 100,
                'defence' => 100,
                'health' => 100,
                'maxHealth' => 100,
                'name' => 'Two',
                'speed' => 100,
                'id' => Uuid::uuid4()->toString(),
                'moves' => [],
            ]
        );
        $combat->takeTurn(0, 0);
        self::assertTrue($combat->isWinner());
        self::assertEquals($combat->getCombatantTwo(), $combat->getWinner());
        self::assertEquals(100, $combat->getCombatantTwo()->health);
        self::assertLessThanOrEqual(0, $combat->getCombatantOne()->health);
    }

    public function testThatARandomCombatantGoesFirstIfCombatantsHaveTheSameSpeed()
    {
        $randomiserMock = $this->getMockBuilder(Randomizer::class)
            ->getMock();

        $randomiserMock->expects($this->once())
            ->method('__invoke')
            ->with(1, 2)
            ->willReturn(2);

        $combat = $this->getCombat($randomiserMock,
            [
                'level' => 1,
                'attack' => 1,
                'defence' => 1,
                'health' => 1,
                'maxHealth' => 1,
                'name' => 'One',
                'speed' => 100,
                'id' => Uuid::uuid4()->toString(),
                'moves' => [],
            ],
            [
                'level' => 100,
                'attack' => 100,
                'defence' => 100,
                'health' => 100,
                'maxHealth' => 100,
                'name' => 'Two',
                'speed' => 100,
                'id' => Uuid::uuid4()->toString(),
                'moves' => [],
            ]
        );

        self::assertEquals('Two', $combat->getCombatantGoingFirst());
    }

}
