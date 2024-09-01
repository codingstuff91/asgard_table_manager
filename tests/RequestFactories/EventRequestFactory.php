<?php

namespace Tests\RequestFactories;

class EventRequestFactory
{
    private string $name = 'example';

    private string $description = 'description';

    private string $start_hour = '14:00';

    public static function new(): self
    {
        return new self;
    }

    public function create(array $extra = []): array
    {
        return $extra + [
            'name' => $this->name,
            'description' => $this->description,
            'start_hour' => $this->start_hour,
        ];
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function withStartHour(string $startHour): self
    {
        $this->start_hour = $startHour;

        return $this;
    }
}
