<?php

namespace ChatemonTest\Factory;

use Chatemon\Exception\PropertyDoesNotExistException;
use Chatemon\Factory\CombatantFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CombatantFactoryTest extends TestCase
{

    public function testCreateFillsPropertiesWithCorrectlyTypedArray()
    {
        $combatant = CombatantFactory::create([
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

    public function testCreateThrowsExceptionWithMissingArrayKey()
    {
        self::expectException(PropertyDoesNotExistException::class);
        $combatant = CombatantFactory::create([
            'attack' => 10,
            'defence' => 20,
            'special' => 0,
            'name' => 'Spabby',
        ]);
    }
}
