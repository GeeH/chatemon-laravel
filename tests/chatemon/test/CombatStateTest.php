<?php

namespace ChatemonTest;

use Chatemon\CombatState;
use Chatemon\Exception\InvalidCombatStateException;
use PHPUnit\Framework\TestCase;

class CombatStateTest extends TestCase
{
    public function testFreshCreatesBasicState(): void
    {
        $freshCombatState = CombatState::fresh();
        $turnsProperty = new \ReflectionProperty(CombatState::class, 'turns');
        $winnerProperty = new \ReflectionProperty(CombatState::class, 'winner');
        $turnsProperty->setAccessible(true);
        $winnerProperty->setAccessible(true);

        self::assertSame(0, $turnsProperty->getValue($freshCombatState));
        self::assertFalse($winnerProperty->getValue($freshCombatState));

    }

    public function testThatCombatStateErrorsWithoutTurnsKey(): void
    {
        self::expectException(InvalidCombatStateException::class);
        self::expectExceptionMessage('data expects "turns" key to exist');
        CombatState::fromArray(['turn' => '']);
    }

    public function testThatCombatStateErrorsWithoutWinnerKey(): void
    {
        self::expectException(InvalidCombatStateException::class);
        self::expectExceptionMessage('data expects "winner" key to exist');
        CombatState::fromArray(['turn' => '', 'turns' => 0]);
    }

    public function testThatCombatStateDoesNotErrorWithCorrectTurnValue(): void
    {
        try {
            $combatStateInstance = CombatState::fromArray(['turn' => 'One', 'turns' => 0, 'winner' => false]);
            self::assertInstanceOf(CombatState::class, $combatStateInstance);
        } catch (\AssertionError $assertionError) {
            $this->fail();
        }

        try {
            $combatStateInstance = CombatState::fromArray(['turn' => 'Two', 'turns' => 0, 'winner' => false]);
            self::assertInstanceOf(CombatState::class, $combatStateInstance);
        } catch (\AssertionError $assertionError) {
            $this->fail();
        }
    }

    public function testCombatStateErrorsWhenPassedNegativeTurns(): void
    {
        self::expectException(InvalidCombatStateException::class);
        self::expectExceptionMessage('Turns cannot be negative');
        CombatState::fromArray(['turn' => 'One', 'turns' => -1, 'winner' => false]);
    }

    public function testGetTurnsReturnsPropertyValue(): void
    {
        $testValue = random_int(11, 99);

        $turnsProperty = new \ReflectionProperty(CombatState::class, 'turns');
        $turnsProperty->setAccessible(true);

        $combatStateInstance = CombatState::fresh();
        $turnsProperty->setValue($combatStateInstance, $testValue);

        self::assertSame($testValue, $combatStateInstance->getTurns());
    }

    public function testIncrementTurnCountUpdatesTurnCount()
    {
        $combatStateInstance = CombatState::fresh();
        self::assertSame(0, $combatStateInstance->getTurns());

        for ($i = 1; $i <= 5; $i++) {
            $combatStateInstance->incrementTurnCount();
            self::assertSame($i, $combatStateInstance->getTurns());
        }
    }

    public function testHasWinnerReturnsPropertyValue(): void
    {
        $testValue = (bool)random_int(0, 1);

        $winnerProperty = new \ReflectionProperty(CombatState::class, 'winner');
        $winnerProperty->setAccessible(true);

        $combatStateInstance = CombatState::fresh();
        $winnerProperty->setValue($combatStateInstance, $testValue);

        self::assertSame($testValue, $combatStateInstance->hasWinner());
    }

    public function testMarkWonSetsWinnerToTrue(): void
    {
        $combatStateInstance = CombatState::fresh();
        self::assertTrue($combatStateInstance->hasWinner());

        $combatStateInstance->markWon();
        self::assertTrue($combatStateInstance->hasWinner());
    }
}
