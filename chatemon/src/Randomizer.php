<?php

declare(strict_types=1);

namespace Chatemon;

class Randomizer
{
    public function __invoke(int $minimum, int $maximum): int
    {
        return \random_int($minimum, $maximum);
    }
}
