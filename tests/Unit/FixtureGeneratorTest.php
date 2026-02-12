<?php

namespace Tests\Unit;

use App\Domain\League\Services\FixtureGenerator;
use PHPUnit\Framework\TestCase;

class FixtureGeneratorTest extends TestCase
{
    public function test_generates_6_weeks_12_matches_for_4_teams(): void
    {
        $gen = new FixtureGenerator();
        $weeks = $gen->generateDoubleRoundRobin([1,2,3,4]);

        $this->assertCount(6, $weeks);

        $countMatches = 0;
        foreach ($weeks as $pairs) {
            $this->assertCount(2, $pairs);
            $countMatches += count($pairs);
        }
        $this->assertEquals(12, $countMatches);
    }
}
