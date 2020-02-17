<?php

namespace ChatemonTest\Factory;

use Chatemon\Exception\PropertyDoesNotExistException;
use Chatemon\Factory\CombatantFactory;
use Chatemon\Move;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CombatantFactoryTest extends TestCase
{

    public function testThatFromArrayFillsPropertiesWithCorrectlyTypedArray()
    {
        $combatant = CombatantFactory::fromArray([
            'level' => 42,
            'attack' => 10,
            'defence' => 20,
            'health' => 100,
            'maxHealth' => 100,
            'name' => 'Spabby',
            'speed' => 10,
            'id' => Uuid::uuid4()->toString(),
            'moves' => [],
        ]);

        self::assertEquals(42, $combatant->level);
        self::assertEquals(10, $combatant->attack);
        self::assertEquals(20, $combatant->defence);
        self::assertEquals(100, $combatant->health);
        self::assertEquals('Spabby', $combatant->name);
        self::assertEquals(10, $combatant->speed);
        self::assertCount(3, $combatant->moves);
        self::assertTrue(Uuid::isValid($combatant->id));
    }

    public function testThatFromArrayThrowsExceptionWithMissingArrayKey()
    {
        self::expectException(PropertyDoesNotExistException::class);
        $combatant = CombatantFactory::fromArray([
            'attack' => 10,
            'defence' => 20,
            'special' => 0,
            'name' => 'Spabby',
        ]);
    }

    public function testThatFromArrayCreatesMovesFromPopulatedMovesArray()
    {
        $combatant = CombatantFactory::fromArray([
            'level' => 42,
            'attack' => 10,
            'defence' => 20,
            'health' => 100,
            'maxHealth' => 100,
            'name' => 'Spabby',
            'speed' => 10,
            'id' => Uuid::uuid4()->toString(),
            'moves' => [
                [
                    'name' => 'Move One',
                    'accuracy' => 100,
                    'damage' => 10
                ],
                [
                    'name' => 'Move Two',
                    'accuracy' => 25,
                    'damage' => 25,
                ],
            ],
        ]);

        self::assertCount(2, $combatant->moves);
        self::assertEquals('Move One', $combatant->moves[0]->name);
        self::assertEquals('Move Two', $combatant->moves[1]->name);
    }
}
