<?php

declare(strict_types=1);

namespace Chatemon;

use Chatemon\Exception\CombatAlreadyWonException;
use Chatemon\Exception\CombatNotWonException;
use Chatemon\Exception\MoveDoesNotExistException;
use Exception;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

final class Combat
{
    protected Combatant $combatantOne;
    protected Combatant $combatantTwo;
    protected CombatState $combatState;
    protected string $id;
    private LoggerInterface $logger;
    private Randomizer $randomizer;
    private array $feedback = [];

    /**
     * @throws Exception
     */
    public function __construct(
        Combatant $combatantOne,
        Combatant $combatantTwo,
        CombatState $combatState,
        Randomizer $randomizer,
        LoggerInterface $logger
    ) {
        $this->combatantOne = $combatantOne;
        $this->combatantTwo = $combatantTwo;
        $this->combatState = $combatState;
        $this->id = Uuid::uuid4()->toString();
        $this->randomizer = $randomizer;
        $this->logger = $logger;
    }

    /**
     * @throws CombatAlreadyWonException
     * @throws MoveDoesNotExistException
     */
    public function takeTurn(int $oneMoveIndex, int $twoMoveIndex): void
    {
        $this->feedback = [];

        if ($this->combatState->hasWinner()) {
            throw new CombatAlreadyWonException();
        }

        if (
            !array_key_exists($oneMoveIndex, $this->combatantOne->moves)
            || !array_key_exists($twoMoveIndex, $this->combatantTwo->moves)
        ) {
            throw new MoveDoesNotExistException();
        }

        $combatantGoingFirst = $this->getCombatantGoingFirst();

        /** @var Combatant $attacker */
        $attacker = $this->{'combatant' . $combatantGoingFirst};
        $this->logger->info($attacker->name . ' is going first');
        /** @var Combatant $defender */
        $defender = $this->{'combatant' . ($combatantGoingFirst === 'One' ? 'Two' : 'One')};

        // @todo Messsy - refactor
        $moveIndex = ($combatantGoingFirst === 'One' ? $oneMoveIndex : $twoMoveIndex);
        if (!$this->attackDefender($attacker, $defender, $moveIndex)) {
            // once the attacker has attacked the defender, flip them around so that the attacker becomes the defender
            // and vice versa
            /** @var Combatant $attacker */
            $attacker = $this->{'combatant' . ($combatantGoingFirst === 'One' ? 'Two' : 'One')};
            /** @var Combatant $defender */
            $defender = $this->{'combatant' . ($combatantGoingFirst === 'One' ? 'One' : 'Two')};
            $moveIndex = ($combatantGoingFirst === 'One' ? $twoMoveIndex : $oneMoveIndex);
            $this->attackDefender($attacker, $defender, $moveIndex);
            // THIS WAS BAD CODE - GH 30/03/2020
        }

        $this->combatState->incrementTurnCount();
    }

    /**
     * @todo Make private again and test better
     * @todo Give 100% coverage
     */
    public function getCombatantGoingFirst(): string
    {
        if ($this->combatantOne->speed > $this->combatantTwo->speed) {
            return 'One';
        }

        if ($this->combatantTwo->speed > $this->combatantOne->speed) {
            return 'Two';
        }

        $diceRoll = $this->randomizer->__invoke(1, 2);
        if ($diceRoll === 1) {
            return 'One';
        }

        return 'Two';
    }

    protected function attackDefender(Combatant $attacker, Combatant $defender, int $moveIndex): bool
    {
        $move = $attacker->moves[$moveIndex];
        $this->logger->info('Attacker is ' . $attacker->name);

        /** @var Combatant $defender */
        $this->logger->info('Defender is ' . $defender->name);

        // roll a D100 dice
        $chanceToHit = $this->randomizer->__invoke(1, 100);
        if ($chanceToHit > $move->accuracy) {
            $this->feedback[] =
                "{$attacker->name} attacked {$defender->name} with <strong>{$move->name}</strong> but they missed";
            // we missed, do nothing
            $this->logger->info('Missed attack');
            return false;
        }

        $this->feedback[] =
            "{$attacker->name} attacked {$defender->name} with <strong>{$move->name}</strong> it was effective";

        $damage = $this->calculateDamage($attacker->level, $attacker->attack, $move->damage, $defender->defence);

        $this->logger->info('Damage is ' . $damage);
        $defender->health -= $damage;

        $this->logger->info('Defender\'s health is now ' . $defender->health);

        if ($defender->health < 1) {
            $this->combatState->markWon();
            $this->feedback[] = "<strong>{$defender->name} has fainted!</strong>";
            return true;
        }

        return false;
    }

    public function calculateDamage(
        int $attackerLevel,
        int $attackerAttack,
        int $moveDamage,
        int $defenderDefence
    ): int {
        /**
         *  ((2A/5+2)*B*C)/D)/50)+2)*X)*Y/10)*Z)/255
         * A = attacker's Level
         * B = attacker's Attack or Special
         * C = attack Power
         * D = defender's Defense or Special
         * X = same-Type attack bonus (1 or 1.5)
         * Y = Type modifiers (40, 20, 10, 5, 2.5, or 0)
         * Z = a random number between 217 and 255
         */

        return (int)floor(
            floor(
                floor(
                    floor(
                        floor(
                            floor(
                                floor(
                                    floor(
                                        floor(
                                            2 * $attackerLevel / 5 + 2
                                        ) * $attackerAttack * $moveDamage
                                    )
                                    / $defenderDefence
                                ) / 50
                            ) + 2
                        ) * 1 * 10
                    ) / 10
                ) * $this->randomizer->__invoke(217, 255)
            ) / 255
        );
    }

    public function getTurns(): int
    {
        return $this->combatState->getTurns();
    }

    public function isWinner(): bool
    {
        return $this->combatState->hasWinner();
    }

    /**
     * @throws CombatNotWonException
     */
    public function getWinner(): Combatant
    {
        if (!$this->combatState->hasWinner()) {
            throw new CombatNotWonException();
        }
        return $this->combatantOne->health >= 1 ? $this->combatantOne : $this->combatantTwo;
    }

    public function toArray(): array
    {
        $return = array_merge(
            [
                'combatantOne' => $this->getCombatantOne()->toArray(),
                'combatantTwo' => $this->getCombatantTwo()->toArray(),
                'id' => $this->getId(),
                'feedback' => $this->feedback,
            ],
            $this->combatState->toArray(),
        );

        return $return;
    }

    public function getCombatantOne(): Combatant
    {
        return $this->combatantOne;
    }

    public function getCombatantTwo(): Combatant
    {
        return $this->combatantTwo;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
