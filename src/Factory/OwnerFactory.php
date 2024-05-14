<?php declare(strict_types=1);

namespace PamutProba\Factory;

use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Model\Factory\Factory;
use PamutProba\Core\Model\Model;
use PamutProba\Core\Validation\Id;
use PamutProba\Core\Validation\IsEmail;
use PamutProba\Core\Validation\StringLength;
use PamutProba\Entity\Owner;
use PamutProba\Validation\IsOwner;

class OwnerFactory extends Factory
{
    public function __construct(?IDatabaseService $databaseService = null)
    {
        $this->model = Model::for(Owner::class, $databaseService);
    }

    public static function validators(): array
    {
        return [
            "id" => [new IsOwner(), new Id(Owner::class)],
            "name" => [new StringLength(3, 150)],
            "email" => [new StringLength(5, 150), new IsEmail()]
        ];
    }

    public function get(int $id): Owner|null
    {
        $entity = $this->model->get($id);
        if ($entity === null)
        {
            return null;
        }

        return Owner::cast($entity);
    }

    public function getBy(string $field, mixed $value): Owner|null
    {
        $entity = $this->model->getBy($field, $value);
        if ($entity === null)
        {
            return null;
        }

        return Owner::cast($entity);
    }

    /**
     * @param int $start
     * @param int $limit
     * @return Owner[]
     */
    public function list(int $start = 0, int $limit = 0): array
    {
        $entities = $this->model->list($start, $limit);

        $result = [];
        foreach ($entities as $entity)
        {
            $owner = Owner::cast($entity);
            if ($owner === null)
            {
                continue;
            }

            $result[] = $owner;
        }

        return $result;
    }

    public function save(Entity $entity): Owner
    {
        if (!($entity instanceof Owner))
        {
            throw new \Exception("Invalid entity type '" . get_class($entity) . "'");
        }

        $entity = $this->model->save($entity);

        return Owner::cast($entity);
    }
}