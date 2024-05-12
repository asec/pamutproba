<?php

namespace PamutProba\Entity\Model;

use PamutProba\Database\DatabaseEntityType;
use PamutProba\Entity\Entity;
use PamutProba\Entity\Owner;
use PamutProba\Entity\Project;
use PamutProba\Entity\Status;

class ProjectModel extends Model
{
    protected static string $entityType = Project::class;
    protected static DatabaseEntityType $dbEntityType = DatabaseEntityType::Project;

    protected static function groupRawData(array $data): array
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
     * @return Entity[]
     * @throws \Exception
     */
    public static function list(int $start = 0, int $limit = 10): array
    {
        $rawData = static::db()->entity(static::$dbEntityType)->list($start, $limit);
        $result = [];
        foreach ($rawData as $data)
        {
            list($projectData, $statusData, $ownerData) = static::groupRawData($data);
            $projectData["status"] = Status::from($statusData);
            $projectData["owner"] = Owner::from($ownerData);
            $result[] = Project::from($projectData);
        }

        return $result;
    }

    public static function get(int $id): Entity|null
    {
        $rawData = static::db()->entity(static::$dbEntityType)->get($id);
        if ($rawData === false)
        {
            return null;
        }

        list($projectData, $statusData, $ownerData) = static::groupRawData($rawData);
        $projectData["status"] = Status::from($statusData);
        $projectData["owner"] = Owner::from($ownerData);

        return Project::from($projectData);
    }
}