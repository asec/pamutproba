<?php

namespace PamutProba\Entity;

class Project extends Entity
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public Status $status,
        public Owner $owner
    ){}

    public static function random(): Project
    {
        return new Project(
            rand(1, 100),
            "Lorem ipsum dolor sit amet",
            "Lorem ipsum dolor sit amet.",
            Status::random(),
            Owner::random()
        );
    }
}