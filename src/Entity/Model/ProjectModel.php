<?php

namespace PamutProba\Entity\Model;

use PamutProba\Entity\Entity;
use PamutProba\Entity\Model\Validation\Id;
use PamutProba\Entity\Model\Validation\IsProject;
use PamutProba\Entity\Model\Validation\StringLength;
use PamutProba\Entity\Model\Validation\ValidEntity;
use PamutProba\Entity\Owner;
use PamutProba\Entity\Project;
use PamutProba\Entity\Status;

class ProjectModel extends Model
{
    protected function validators(): array
    {
        return [
            "id" => [new IsProject(), new Id($this)],
            "title" => [new StringLength(3, 150)],
            "description" => [new StringLength(1, 1000)],
            "status" => [new ValidEntity(Status::class)],
            "owner" => [new ValidEntity(Owner::class)]
        ];
    }

    protected function groupRawData(array $data): array
    {
        $projectData = [];
        $statusData = [];
        $ownerData = [];

        foreach ($data as $key => $value)
        {
            if (str_starts_with($key, "psp_status_"))
            {
                $key = substr($key, strlen("psp_status_"));
                $statusData[$key] = $value;
                continue;
            }

            if (str_starts_with($key, "pop_owner_"))
            {
                $key = substr($key, strlen("pop_owner_"));
                $ownerData[$key] = $value;
                continue;
            }

            $projectData[$key] = $value;
        }

        return [$projectData, $statusData, $ownerData];
    }

    /**
     * @param int $start
     * @param int $limit
     * @return Project[]
     * @throws \Exception
     */
    public function list(int $start = 0, int $limit = 0): array
    {
        $store = $this->store();
        if ($this->filterType !== null)
        {
            $store->filterByRelation($this->filterType, $this->filterValue->id);
        }
        $rawData = $store->list($start, $limit);
        $result = [];
        foreach ($rawData as $data)
        {
            list($projectData, $statusData, $ownerData) = static::groupRawData($data);
            $projectData["status"] = $statusData;
            $projectData["owner"] = $ownerData;
            $result[] = Project::from($projectData);
        }

        return $result;
    }

    public function get(int $id): Project|null
    {
        $rawData = $this->store()->get($id);
        if ($rawData === false)
        {
            return null;
        }

        list($projectData, $statusData, $ownerData) = static::groupRawData($rawData);
        $projectData["status"] = $statusData;
        $projectData["owner"] = $ownerData;

        return Project::from($projectData);
    }
}