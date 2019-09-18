<?php

namespace ActiveTable;


use ActiveTable\Factories\CommandFactory;
use PHPUnit\Framework\TestCase;
use Repo\CrudRepositoryInterface;
use Repo\CollectionInterface;

class DataTableSimpleTest extends TestCase
{

    protected $object;

    protected function setUp()
    {
        $this->object = new DataTableEngine($this->getRepoMock(),"test", new CommandFactory());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getRepoMock() : CrudRepositoryInterface {
        $collectionMock
            = $this->createMock(CollectionInterface::class)
        ;

        $testMock
            = $this->createMock(CrudRepositoryInterface::class);

        $testMock
            ->method("findByCriteria")
            ->willReturn($collectionMock)
        ;

        return $testMock;
    }


    /**
     * @covers DataTableSimple::__construct
     */
    public function test__construct()
    {

        $this->assertTrue(is_a($this->object, DataTableEngine::class));
    }

    /**
     * @covers DataTableSimple::render
     */
    public function test__render()
    {

        $content = $this->object->render();
        //echo PHP_EOL . $content;
        $this->assertEquals( $content , "<TABLE_TOP_CONTROL_HTML><TABLE_HTML><TABLE_BOTTOM_CONTROL_HTML>");
    }

    /**
     * @covers DataTableSimple::render
     */
    public function test__render_form()
    {

        $this->object = new DataTableEngine($this->getRepoMock(),"test", new CommandFactory("FORM_VIEW"));

        $content = $this->object->render();
        //echo PHP_EOL . $content;
        $this->assertEquals( $content , "<FORM_VIEW_HTML>");

    }


    public function test__action_table()
    {

        $this->object = new DataTableEngine($this->getRepoMock(),"test", new CommandFactory("TABLE_ACTION"));

        $content = $this->object->render();

       // echo PHP_EOL . $content;

        $this->assertEquals( $content , "<TABLE_ACTION_PROCESS>");
    }

}
