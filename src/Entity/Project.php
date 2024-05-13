<?php declare(strict_types=1);

namespace PamutProba\Entity;

class Project extends Entity
{
    public function __construct(
        public ?int $id,
        public string $title,
        public string $description,
        public Status $status,
        public Owner $owner
    ){}

    public static function from(array $data): static
    {
        $data["status"] = Status::from($data["status"]);
        $data["owner"] = Owner::from($data["owner"]);

        return parent::from($data);
    }

    public static function random(): Project
    {
        return new Project(
            null,
            "Lorem ipsum dolor sit amet #" . rand(1, 100),
            "Lorem ipsum dolor sit amet.",
            Status::random(),
            Owner::random()
        );
    }
}