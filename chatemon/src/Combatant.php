<?php

declare(strict_types=1);

namespace Chatemon;

final class Combatant
{
    public int $level = 1;
    public int $attack = 1;
    public int $defence = 1;
    public int $maxHealth = 100;
    public int $health = 100;
    public string $name = '';
    public int $speed = 10;
    public string $id = '';
    /** @var Move[] $moves */
    public array $moves = [];

    public function toArray(): array
    {
        $combatantArray = [
            'level' => $this->level,
            'attack' => $this->attack,
            'defence' => $this->defence,
            'maxHealth' => $this->maxHealth,
            'health' => $this->health,
            'name' => $this->name,
            'speed' => $this->speed,
            'id' => $this->id,
            'moves' => [],
        ];

        foreach ($this->moves as $move) {
            $combatantArray['moves'][] = (array)$move;
        }

        return $combatantArray;
    }
}
