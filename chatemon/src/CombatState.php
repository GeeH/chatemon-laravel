<?php

declare(strict_types=1);

namespace Chatemon;

use Chatemon\Exception\InvalidCombatStateException;
use Symfony\Component\Config\Definition\Exception\InvalidTypeException;

final class CombatState
{
    protected int $turns = 0;
    protected bool $winner = false;

    protected function __construct(int $turns, bool $winner)
    {
        $this->turns = $turns;
        $this->winner = $winner;
    }

    /**
     * @psalm-param array{turns:int, winner:bool} $data
     */
    public static function fromArray(array $data): CombatState
    {
        if (!array_key_exists('turns', $data)) {
            throw new Exception\InvalidCombatStateException('data expects "turns" key to exist');
        }

        if (!array_key_exists('winner', $data)) {
            throw new InvalidCombatStateException('data expects "winner" key to exist');
        }

        if ($data['turns'] < 0) {
            throw new InvalidCombatStateException('Turns cannot be negative');
        }

        return new CombatState($data['turns'], $data['winner']);
    }

    public static function fresh(): CombatState
    {
        return new CombatState(0, false);
    }

    public function incrementTurnCount(): int
    {
        $this->turns++;
        return $this->turns;
    }

    public function toArray(): array
    {
        return [
            'turns' => $this->getTurns(),
            'winner' => $this->hasWinner(),
        ];
    }

    public function getTurns(): int
    {
        return $this->turns;
    }

    public function hasWinner(): bool
    {
        return $this->winner;
    }

    public function markWon(): void
    {
        $this->winner = true;
    }
}
