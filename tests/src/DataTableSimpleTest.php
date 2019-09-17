<?php

namespace ActiveTable;


use PHPUnit\Framework\TestCase;
use Repo\CrudRepositoryInterface;
use Repo\CollectionInterface;

class DataTableSimpleTest extends TestCase
{

    protected $object;

    protected function setUp()
    {
        $collectionMock
            = $this->createMock(CollectionInterface::class)
        ;

        $testMock
            = $this->createMock(CrudRepositoryInterface::class);

         $testMock
            ->method("findByCriteria")
            ->willReturn($collectionMock)
        ;

        $this->object = new DataTableEngine($testMock,"test");
    }


    /**
     * @covers DataTableSimple::__construct
     */
    public function test__construct()
    {

        $this->assertTrue(is_a($this->object, DataTableEngine::class));
    }


    public function test__render()
    {
        dump(
            $this->object->render()
        );
    }

}
