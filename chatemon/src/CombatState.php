<?php declare(strict_types=1);


namespace Chatemon;


final class CombatState
{
    protected int $turns = 0;
    protected bool $winner = false;

    protected function __construct(int $turns = 0, bool $winner = false)
    {
        $this->turns = $turns;
        $this->winner = $winner;
    }

    public static function fromArray(array $data): CombatState
    {
        assert(array_key_exists('turns', $data), 'data expects "turns" key to exist');
        assert(array_key_exists('winner', $data), 'data expects "winner" key to exist');
        assert($data['turns'] >= 0, 'Turns cannot be negative');

        return new CombatState($data['turns'], $data['winner']);
    }

    public static function fresh(): CombatState
    {
        return new CombatState(0, false);
    }

    public function getTurns(): int
    {
        return $this->turns;
    }

    public function hasWinner(): bool
    {
        return $this->winner;
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

    public function markWon(): void
    {
        $this->winner = true;
    }
    
}