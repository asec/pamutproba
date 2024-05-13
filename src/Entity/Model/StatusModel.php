<?php declare(strict_types=1);

namespace PamutProba\Entity\Model;

use PamutProba\Entity\Entity;
use PamutProba\Entity\Model\Validation\Id;
use PamutProba\Entity\Model\Validation\IsStatus;
use PamutProba\Entity\Model\Validation\NotNull;
use PamutProba\Entity\Model\Validation\StringLength;
use PamutProba\Entity\Status;

class StatusModel extends Model
{
    protected function validators(): array
    {
        return [
            "id" => [new IsStatus(), new NotNull(), new Id($this)],
            "key" => [new StringLength(1, 45)],
            "name" => [new StringLength(3, 45)]
        ];
    }

    /**
     * @param int $start
     * @param int $limit
     * @return Entity[]
     * @throws \Exception
     */
    public function list(int $start = 0, int $limit = 0): array
    {
        $rawData = $this->store()->list($start, $limit);

        $result = [];
        foreach ($rawData as $data)
        {
            $result[] = Status::from($data);
        }

        return $result;
    }
}