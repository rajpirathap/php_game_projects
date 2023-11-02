<?php
require(__DIR__ . '/../Grid.php');
use raj\gol\Grid;

class GridTest extends PHPUnit_Framework_TestCase {

    protected $grid;

    protected function setUp()
    {
        $this->grid = new Grid();
    }
    public function testCanSetAndGetSizeProperty()
    {
        $this->grid->setSize(20);

        // Assert
        $this->assertEquals(20, $this->grid->getSize());
        $this->assertNotEquals(21, $this->grid->getSize());

    }

    public function testGridThrowsExceptionIfNoSizeSet()
    {
        $this->setExpectedException('InvalidArgumentException', 'Grid size not set');
        $this->grid->makeGrid();
    }

    public function testGridReturnsAnArray()
    {
        $grid = $this->grid->setSize(10)->makeGrid();
        $this->assertInternalType('array', $grid);
    }
}
 
