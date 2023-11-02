<?php

namespace raj\gol;

class Grid {

    protected $size = null;
    protected $grid = [];

    /**
     * @return array
     */
    public function getGrid()
    {
        return $this->grid;
    }


    /**
     * @param $grid
     * @return $this
     */
    public function setGrid($grid = [])
    {
        $this->grid = $grid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param $size
     * @return $this
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    public function makeGrid()
    {
        if(!$this->size) {
            throw new \InvalidArgumentException('Grid size not set');
        }

        $grid = [];
        for ($row = 1; $row <= $this->size; $row++) {
            for ($col = 1; $col <= $this->size; $col++) {
                $grid[$row][$col] = '';
            }
        }
        $this->grid = $grid;
        return $grid;
    }

}
