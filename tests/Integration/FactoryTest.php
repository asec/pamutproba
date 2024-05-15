<?php declare(strict_types=1);

namespace Integration;

use _PamutProbaTest\Core\Database\InMemory\InMemoryDatabaseService;
use PamutProba\Core\Model\Model;
use PamutProba\Database\DatabaseEntityType;
use PamutProba\Entity\Owner;
use PamutProba\Entity\Project;
use PamutProba\Entity\Status;
use PamutProba\Factory\OwnerFactory;
use PamutProba\Factory\ProjectFactory;
use PamutProba\Factory\StatusFactory;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        // Default store-t itt nem szabad használni, minden teszt esetnek külön adatbázis kell
        //Model::setDefaultStore(new InMemoryDatabaseService());
        Model::bind(Owner::class, DatabaseEntityType::Owner);
        Model::bind(Status::class, DatabaseEntityType::Status);
        Model::bind(Project::class, DatabaseEntityType::Project);
    }

    public function testCreate(): void
    {
        $factory = new OwnerFactory(new InMemoryDatabaseService());
        $count = 10;

        for ($i = 0; $i < $count; $i++)
        {
            $factory->save(Owner::random());
        }

        $this->assertEquals($count, $factory->count());

        $list = $factory->list();
        foreach ($list as $item)
        {
            $this->assertInstanceOf(Owner::class, $item);
        }
    }

    public function testPagination(): void
    {
        $factory = new OwnerFactory(new InMemoryDatabaseService());
        for ($i = 0; $i < 20; $i++)
        {
            $owner = Owner::random();
            $factory->save($owner);
        }

        $perPage = 7;
        $count = $factory->count();
        $maxPage = intval(ceil($count / $perPage));
        $currentId = 1;
        for ($i = 0; $i < $maxPage; $i++)
        {
            $list = $factory->list($i * $perPage, $perPage);
            foreach ($list as $item)
            {
                $this->assertInstanceOf(Owner::class, $item);
                $this->assertEquals($currentId++, $item->id);
            }
        }

        $list = $factory->list(10);
        $currentId = 1;
        foreach ($list as $item)
        {
            $this->assertInstanceOf(Owner::class, $item);
            $this->assertEquals($currentId++, $item->id);
        }

        $list = $factory->list(0, 5);
        $currentId = 1;
        foreach ($list as $item)
        {
            $this->assertInstanceOf(Owner::class, $item);
            $this->assertEquals($currentId++, $item->id);
        }
    }

    public function testUpdate(): void
    {
        $factory = new OwnerFactory(new InMemoryDatabaseService());
        $count = 10;

        for ($i = 0; $i < $count; $i++)
        {
            $factory->save(Owner::random());
        }

        $owner = $factory->get(3);
        $prevName = $owner->name;
        $this->assertInstanceOf(Owner::class, $owner);
        $this->assertEquals(3, $owner->id);

        $owner->name = "random";
        $owner = $factory->save($owner);
        $this->assertEquals($count, $factory->count());
        $this->assertNotEquals($prevName, $owner->name);
        $this->assertNotEquals($prevName, $factory->get(3)->name);
        $this->assertEquals($owner->name, $factory->get(3)->name);
    }

    public function testDelete(): void
    {
        $factory = new OwnerFactory(new InMemoryDatabaseService());
        $count = 20;

        for ($i = 0; $i < $count; $i++)
        {
            $factory->save(Owner::random());
        }

        $owner = $factory->get(10);
        $this->assertInstanceOf(Owner::class, $owner);
        $this->assertEquals(10, $owner->id);

        $factory->delete($owner);
        $this->assertEquals($count - 1, $factory->count());
        $this->assertNull($factory->get(10));

        $owner = $factory->get(9);
        $this->assertInstanceOf(Owner::class, $owner);
        $this->assertEquals(9, $owner->id);

        $owner = $factory->get(11);
        $this->assertInstanceOf(Owner::class, $owner);
        $this->assertEquals(11, $owner->id);

        $factory->save(Owner::random());
        $this->assertEquals($count, $factory->count());

        $owner = $factory->get(21);
        $this->assertEquals(21, $owner->id);
        $this->assertNull($factory->get(22));
    }

    public function testStatusConstraint(): void
    {
        $factory = new StatusFactory(new InMemoryDatabaseService());
        $origiStatus = Status::random();
        $status = $factory->save($origiStatus);

        $status->key .= "2";
        $factory->save($status);
        $this->assertEquals(1, $factory->count());

        $status->id = 100;
        $factory->save($status);
        $this->assertEquals(1, $factory->count());

        $status->key = "test";
        $factory->save($status);

        $this->expectExceptionMessage("Unique constraint error");
        $factory->save(Status::random());
    }

    public function testRelations(): void
    {
        $factory = new ProjectFactory(new InMemoryDatabaseService());
        $statusFactory = new StatusFactory(new InMemoryDatabaseService());
        $count = 10;

        $status = $statusFactory->save(Status::random());
        for ($i = 0; $i < $count; $i++)
        {
            $project = Project::random();
            $project->status = $status;
            $factory->save($project);
        }

        $status->key = "test2";
        $project = $factory->get(1);
        // Itt nincs automatikus propagáció a státusz id alapján a projects táblába. Egyik kapcsolaton sincs
        $project->status = $statusFactory->save($status);
        $factory->save($project);
        $this->assertEquals($factory->get(1)->status->id, $status->id);
        $this->assertEquals($factory->get(1)->status->key, $status->key);
        $this->assertEquals($factory->get(2)->status->id, $status->id);
        $this->assertNotEquals($factory->get(2)->status->key, $status->key);
    }

    public function testFilterByRelations(): void
    {
        $factory = new ProjectFactory(new InMemoryDatabaseService());
        $count = 11;

        $statusFactory = new StatusFactory(new InMemoryDatabaseService());
        $statuses = [
            Status::random(),
            Status::random()
        ];
        $statuses[1]->key = "in-progress";
        foreach ($statuses as &$status)
        {
            $status = $statusFactory->save($status);
        }

        for ($i = 0; $i < $count; $i++)
        {
            $project = Project::random();
            $project->status = $statuses[$i % 2];
            $factory->save($project);
        }

        $factory->filterByRelation(DatabaseEntityType::Status, $statuses[0]);
        $this->assertEquals(intval(ceil($count / 2)), $factory->count());

        $list = $factory->list();
        foreach ($list as $item)
        {
            $this->assertInstanceOf(Status::class, $item->status);
            $this->assertEquals($statuses[0]->key, $item->status->key);
        }

        $list = $factory->list();
        foreach ($list as $item)
        {
            $this->assertInstanceOf(Status::class, $item->status);
            $this->assertEquals($statuses[0]->key, $item->status->key);
        }

        $factory->filterByRelation(DatabaseEntityType::Status, $statuses[1]);
        $this->assertEquals(0, $factory->count());
    }
}