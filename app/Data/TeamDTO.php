<?php

namespace App\Data;

final class TeamDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $power,
    ) {}

    public static function fromArray(array $a): self
    {
        return new self((int)$a['id'], (string)$a['name'], (int)$a['power']);
    }
}
