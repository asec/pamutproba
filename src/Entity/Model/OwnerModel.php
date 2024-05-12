<?php

namespace PamutProba\Entity\Model;

use PamutProba\Entity\Model\Validation\Id;
use PamutProba\Entity\Model\Validation\IsEmail;
use PamutProba\Entity\Model\Validation\IsOwner;
use PamutProba\Entity\Model\Validation\StringLength;
use PamutProba\Entity\Owner;

class OwnerModel extends Model
{
    protected function validators(): array
    {
        return [
            "id" => [new IsOwner(), new Id($this)],
            "name" => [new StringLength(3, 150)],
            "email" => [new StringLength(3, 150), new IsEmail()]
        ];
    }

    public function list(int $start = 0, int $limit = 0): array
    {
        $rawData = $this->store()->list($start, $limit);

        $result = [];
        foreach ($rawData as $data)
        {
            $result[] = Owner::from($data);
        }

        return $result;
    }

    public function get(int $id): Owner|null
    {
        $data = $this->store()->get($id);
        if ($data === false)
        {
            return null;
        }

        return call_user_func(array($this->entityType, "from"), $data);
    }
}