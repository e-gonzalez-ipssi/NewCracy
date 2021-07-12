<?php

namespace App\Entity;

class Tag {

    private int $id;
    private string $tagsName;

    public function __construct(int $id, string $tagsName)
    {
        $this->id = $id;
        $this->tagsName = $tagsName;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->tagsName;
    }

    public function setName(string $tagsName) {
        $this->tagsName = $tagsName;
    }

    public function arrayify(): array {
        return [
            "id" => $this->id,
            "name" => $this->name,
        ];
    }
}
