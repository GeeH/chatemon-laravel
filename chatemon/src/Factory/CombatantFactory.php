<?php

declare(strict_types=1);

namespace Chatemon\Factory;

use Chatemon\Combatant;
use Chatemon\Exception\PropertyDoesNotExistException;
use Chatemon\Move;

final class CombatantFactory
{
    /**
     * @throws PropertyDoesNotExistException
     */
    public static function fromArray(array $data): Combatant
    {
        $combatant = new Combatant();

        foreach (get_class_vars(get_class($combatant)) as $property => $value) {
            if (!array_key_exists($property, $data)) {
                throw new PropertyDoesNotExistException('Property ' . $property . ' does not exist in data');
            }
            $combatant->$property = $data[$property];
        }

        if (empty($data['moves'])) {
            $move = new Move();
            $move->name = 'Var Dump';
            $move->accuracy = 100;
            $move->damage = 10;
            $combatant->moves[] = $move;

            $move = new Move();
            $move->name = 'Step Debug';
            $move->accuracy = 75;
            $move->damage = 25;
            $combatant->moves[] = $move;

            $move = new Move();
            $move->name = 'STACK OVERFLOW!';
            $move->accuracy = 50;
            $move->damage = 50;
            $combatant->moves[] = $move;

            return $combatant;
        }


        $moveObjects = [];
        foreach($data['moves'] as $moveData) {
            $move = new Move();
            $move->name = $moveData['name'];
            $move->accuracy = $moveData['accuracy'];
            $move->damage = $moveData['damage'];
            $moveObjects[] = $move;
        }

        $combatant->moves = $moveObjects;
        return $combatant;
    }
}
