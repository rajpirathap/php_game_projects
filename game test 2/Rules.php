<?php

error_reporting(E_ALL);

class RulesOccupied extends Exception { }

class Rules {

  public $tick;

  function __construct($width, $height) {
    $this->width = $width;
    $this->height = $height;
    $this->tick = 0;
    $this->CellElements = array();
    $this->_directions = array(
      array(-1, 1),  array(0, 1),  array(1, 1), // above
      array(-1, 0),                array(1, 0), // sides
      array(-1, -1), array(0, -1), array(1, -1) // below
    );

    $this->populate_grid_CellElements();
    $this->set_prepopulate_neighbours();
  }

  public function _draw_tick() {
    // determine the action for all CellElements
    foreach ($this->CellElements as $CellElement) {
      $alive_neighbours = $this->neighbours_alive_around($CellElement);
      if (!$CellElement->alive && $alive_neighbours == 3) {
        $CellElement->next_state = 1;
      } else if ($alive_neighbours < 2 || $alive_neighbours > 3) {
        $CellElement->next_state = 0;
      }
    }


    foreach ($this->CellElements as $CellElement) {
      if ($CellElement->next_state == 1) {
        $CellElement->alive = true;
      } else if ($CellElement->next_state == 0) {
        $CellElement->alive = false;
      }
    }

    $this->tick += 1;
  }


  public function render_window() {
    $rendering = '';
    for ($y = 0; $y <= $this->height; $y++) {
      for ($x = 0; $x <= $this->width; $x++) {
        $CellElement = $this->CellElement_at($x, $y);
        $rendering .= $CellElement->to_char();
      }
      $rendering .= "\n";
    }
    return $rendering;

  }

  private function set_prepopulate_neighbours() {
    foreach ($this->CellElements as $CellElement) {
      $this->neighbours_surround($CellElement);
    }
  }

  private function neighbours_surround($CellElement) {
    if ($CellElement->neighbours == null) {
      $CellElement->neighbours = array();
      foreach ($this->_directions as $set) {
        $neighbour = $this->CellElement_at(
          ($CellElement->x + $set[0]),
          ($CellElement->y + $set[1])
        );
        if ($neighbour != null) {
          $CellElement->neighbours[] = $neighbour;
        }
      }
    }

    return $CellElement->neighbours;
  }


  private function neighbours_alive_around($CellElement) {
    $alive_neighbours = 0;
    foreach ($this->neighbours_surround($CellElement) as $neighbour) {
      if ($neighbour->alive) {
        $alive_neighbours++;
      }
    }
    return $alive_neighbours;
  }
  private function populate_grid_CellElements() {
    for ($y = 0; $y <= $this->height; $y++) {
      for ($x = 0; $x <= $this->width; $x++) {
        $alive = (rand(0, 100) <= 20);
        $this->add_CellElement($x, $y, $alive);
      }
    }
  }

  private function CellElement_at($x, $y) {
    if (isset($this->CellElements["$x-$y"])) {
      return $this->CellElements["$x-$y"];
    }
  }

  private function add_CellElement($x, $y, $alive = false) {
    if ($this->CellElement_at($x, $y) != null) {
      throw new RulesOccupied;
    }

    $CellElement = new CellElement($x, $y, $alive);
    $this->CellElements["$x-$y"] = $CellElement;
    return $this->CellElement_at($x, $y);
  }





}

class CellElement {

  var $x, $y, $alive, $next_state, $neighbours;

  function __construct($x, $y, $alive = false) {
    $this->x = $x;
    $this->y = $y;
    $this->alive = $alive;
    $this->next_state = null;
    $this->neighbours = null;
  }

  public function to_char() {
    return ($this->alive ? 'o' : ' ');
  }

}

?>
