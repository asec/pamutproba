<?php declare(strict_types=1);

namespace PamutProba\Core\Model;

use PamutProba\Core\Database\IDatabaseEntity;
use PamutProba\Core\Database\IDatabaseService;
use PamutProba\Core\Entity\Entity;
use PamutProba\Core\Exception\ValidationException;
use PamutProba\Core\Validation\IValidator;
use PamutProba\Database\DatabaseEntityType;

final class Model implements IModel
{
    /**
     * @var array<string, DatabaseEntityType>
     */
    protected static array $classMap = [];
    /**
     * @var array<string, array<string, Model>>
     */
    protected static array $cache = [];
    /**
     * @var array<string, array<string, IValidator[]>>
     */
    protected static array $validatorCache = [];
    protected static IDatabaseService $defaultStore;
    protected ?DatabaseEntityType $filterType = null;
    protected ?Entity $filterValue = null;

    /**
     * @param string $entityType
     * @param IDatabaseService $store
     * @param array<string, IValidator[]> $validators
     */
    protected function __construct(
        protected string $entityType,
        protected IDatabaseService $store,
        protected array $validators = []
    )
    {}

    public static function for(string $entityType, ?IDatabaseService $store = null): self
    {
        if (isset(self::$cache[$entityType]) && $cache = self::$cache[$entityType])
        {
            if ($store !== null)
            {
                $storageClass = get_class($store);
                if (isset($cache[$storageClass]))
                {
                    return $cache[$storageClass];
                }
            }
            else
            {
                $storageClass = get_class(self::$defaultStore);
                $cachedStorageClasses = array_keys($cache);
                if (in_array($storageClass, $cachedStorageClasses))
                {
                    return $cache[$storageClass];
                }
                else if (count($cachedStorageClasses) > 0)
                {
                    return $cache[array_shift($cachedStorageClasses)];
                }
            }
        }

        $storageClass = get_class($store ?? self::$defaultStore);
        $model = new self($entityType, $store ?? self::$defaultStore, self::$validatorCache[$entityType] ?? []);
        self::$cache[$entityType][$storageClass] = $model;

        return $model;
    }

    public static function setDefaultStore(IDatabaseService $store): void
    {
        self::$defaultStore = $store;
    }

    /**
     * @param string $entityType
     * @param DatabaseEntityType $databaseEntityType
     * @param array<string, IValidator[]> $validators
     * @return void
     */
    public static function bind(string $entityType, DatabaseEntityType $databaseEntityType, array $validators = []): void
    {
        self::$classMap[$entityType] = $databaseEntityType;
        self::$validatorCache[$entityType] = $validators;
    }

    protected function databaseEntityType(): DatabaseEntityType
    {
        return self::$classMap[$this->entityType];
    }

    protected function entity(): IDatabaseEntity
    {
        return $this->store->entity($this->databaseEntityType());
    }

    public function type(): string
    {
        return $this->entityType;
    }

    /**
     * @param Entity $entity
     * @return void
     * @throws ValidationException
     */
    public function validate(Entity $entity): void
    {
        foreach ($this->validators as $field => $validatorObjects)
        {
            foreach ($validatorObjects as $validator)
            {
                $validator($field, $entity);
            }
        }
    }

    public function filterByRelation(DatabaseEntityType $databaseEntityType, Entity $entity): self
    {
        $this->filterType = $databaseEntityType;
        $this->filterValue = $entity;

        return $this;
    }

    public function count(): int
    {
        $store = $this->entity();
        if ($this->filterType !== null)
        {
            $store->filterByRelation($this->filterType, $this->filterValue->id);
        }
        return $store->count();
    }

    /**
     * @param int $start
     * @param int $limit
     * @return Entity[]
     */
    public function list(int $start = 0, int $limit = 0): array
    {
        $store = $this->entity();
        if ($this->filterType !== null)
        {
            $store->filterByRelation($this->filterType, $this->filterValue->id);
        }
        $rawData = $store->list($start, $limit);

        $result = [];
        foreach ($rawData as $data)
        {
            $result[] = call_user_func(array($this->entityType, "from"), $data);
        }

        return $result;
    }

    public function get(int $id): Entity|null
    {
        $data = $this->entity()->get($id);
        if ($data === false)
        {
            return null;
        }

        return call_user_func(array($this->entityType, "from"), $data);
    }

    public function getBy(string $field, mixed $value): Entity|null
    {
        $data = $this->entity()->getBy($field, $value);
        if ($data === false)
        {
            return null;
        }

        return call_user_func(array($this->entityType, "from"), $data);
    }

    /**
     * @throws ValidationException
     */
    public function save(Entity $entity): Entity
    {
        $this->validate($entity);
        $data = $this->entity()->save($entity->toArray());

        return call_user_func(array($this->entityType, "from"), $data);
    }

    public function delete(Entity $entity): void
    {
        $this->entity()->delete($entity->id);
    }
}