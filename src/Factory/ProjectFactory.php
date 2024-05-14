<?php declare(strict_types=1);

namespace PamutProba\Factory;

use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Model\Factory\Factory;
use PamutProba\Core\Model\Model;
use PamutProba\Core\Validation\Id;
use PamutProba\Core\Validation\StringLength;
use PamutProba\Core\Validation\ValidEntity;
use PamutProba\Entity\Owner;
use PamutProba\Entity\Project;
use PamutProba\Entity\Status;
use PamutProba\Validation\IsProject;

class ProjectFactory extends Factory
{
    public function __construct(?IDatabaseService $databaseService = null)
    {
        $this->model = Model::for(Project::class, $databaseService);
    }

    public static function validators(): array
    {
        return [
            "id" => [new IsProject(), new Id(Project::class)],
            "title" => [new StringLength(3, 150)],
            "description" => [new StringLength(1, -1)],
            "status" => [new ValidEntity(Status::class)],
            "owner" => [new ValidEntity(Owner::class)]
        ];
    }

    public function get(int $id): Project|null
    {
        $entity = $this->model->get($id);
        if ($entity === null)
        {
            return null;
        }

        return Project::cast($entity);
    }

    public function getBy(string $field, mixed $value): Project|null
    {
        $entity = $this->model->getBy($field, $value);
        if ($entity === null)
        {
            return null;
        }

        return Project::cast($entity);
    }

    /**
     * @param int $start
     * @param int $limit
     * @return Project[]
     */
    public function list(int $start = 0, int $limit = 0): array
    {
        $entities = $this->model->list($start, $limit);

        $result = [];
        foreach ($entities as $entity)
        {
            $owner = Project::cast($entity);
            if ($owner === null)
            {
                continue;
            }

            $result[] = $owner;
        }

        return $result;
    }

    public function save(Entity $entity): Project
    {
        if (!($entity instanceof Project))
        {
            throw new \Exception("Invalid entity type '" . get_class($entity) . "'");
        }

        $entity = $this->model->save($entity);

        return Project::cast($entity);
    }
}