<?php declare(strict_types=1);

namespace Chatemon;

final class Combatant
{
    public int $level;
    public int $attack;
    public int $defence;
    public int $maxHealth;
    public int $health;
    public string $name;
    public int $speed;
    public string $id;
    /** @var Move[] $moves */
    public array $moves = [];
}
