<?php declare(strict_types=1);

namespace PamutProba\Factory;

use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Model\Factory\Factory;
use PamutProba\Core\Model\Model;
use PamutProba\Core\Validation\Id;
use PamutProba\Core\Validation\NotNull;
use PamutProba\Core\Validation\StringLength;
use PamutProba\Entity\Status;
use PamutProba\Validation\IsStatus;

class StatusFactory extends Factory
{
    public function __construct(?IDatabaseService $databaseService = null)
    {
        $this->model = Model::for(Status::class, $databaseService);
    }

    public static function validators(): array
    {
        return [
            "id" => [new IsStatus(), new NotNull(), new Id(Status::class)],
            "key" => [new StringLength(1, 45)],
            "name" => [new StringLength(3, 45)]
        ];
    }

    public function get(int $id): Status|null
    {
        $entity = $this->model->get($id);
        if ($entity === null)
        {
            return null;
        }

        return Status::cast($entity);
    }

    public function getBy(string $field, mixed $value): Status|null
    {
        $entity = $this->model->getBy($field, $value);
        if ($entity === null)
        {
            return null;
        }

        return Status::cast($entity);
    }

    /**
     * @param int $start
     * @param int $limit
     * @return Status[]
     */
    public function list(int $start = 0, int $limit = 0): array
    {
        $entities = $this->model->list($start, $limit);

        $result = [];
        foreach ($entities as $entity)
        {
            $owner = Status::cast($entity);
            if ($owner === null)
            {
                continue;
            }

            $result[] = $owner;
        }

        return $result;
    }

    public function save(Entity $entity): Status
    {
        if (!($entity instanceof Status))
        {
            throw new \Exception("Invalid entity type '" . get_class($entity) . "'");
        }

        $entity = $this->model->save($entity);

        return Status::cast($entity);
    }
}