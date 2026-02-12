<?php

namespace App\Domain\League\Services;

final class FixtureGenerator
{
    /**
     * @param int[] $teamIds
     * @return array<int, array<int, array{0:int,1:int}>> weeks => pairs
     */
    public function generateDoubleRoundRobin(array $teamIds): array
    {
        if (count($teamIds) < 4) {
            throw new \InvalidArgumentException('At least 4 teams required.');
        }
        if (count($teamIds) % 2 !== 0) {
            throw new \InvalidArgumentException('Team count must be even.');
        }

        $single = $this->circleMethod($teamIds);

        $weeks = [];
        $weekNo = 1;

        foreach ($single as $pairs) {
            $weeks[$weekNo++] = $pairs;
        }
        foreach ($single as $pairs) {
            $swapped = array_map(fn($p) => [$p[1], $p[0]], $pairs);
            $weeks[$weekNo++] = $swapped;
        }

        return $weeks;
    }

    /**
     * @param int[] $teamIds
     * @return array<int, array<int, array{0:int,1:int}>>
     */
    private function circleMethod(array $teamIds): array
    {
        $n = count($teamIds);
        $half = (int)($n / 2);

        $fixed = $teamIds[0];
        $rotating = array_slice($teamIds, 1);

        $weeks = [];

        for ($round = 0; $round < ($n - 1); $round++) {
            $left = [$fixed, ...array_slice($rotating, 0, $half - 1)];
            $right = array_reverse(array_slice($rotating, $half - 1));

            $pairs = [];
            for ($i = 0; $i < $half; $i++) {
                $home = $left[$i];
                $away = $right[$i];

                if ($round % 2 === 1) {
                    [$home, $away] = [$away, $home];
                }
                $pairs[] = [$home, $away];
            }

            $weeks[] = $pairs;

            $last = array_pop($rotating);
            array_unshift($rotating, $last);
        }

        return $weeks;
    }
}
