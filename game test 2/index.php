<?php

error_reporting(E_ALL);

require_once('Rules.php');

class Start {

  private static $rule_Width = 100;
  private static $rule_Height = 100;

  public static function run() {
    $rule = new Rules(
      self::$rule_Height,
      self::$rule_Width
    );

    echo $rule->render_window();

    $total_ticks = 0;
    $total_renders = 0;

    while (true) {
      $tick_start_time = microtime(true);
      $rule->_draw_tick();
      $tick_finish_time = microtime(true);
      $tick_time = ($tick_finish_time - $tick_start_time);
      $total_ticks += $tick_time;
      $average_tick = ($total_ticks / $rule->tick);

      $render_start_time = microtime(true);
      $rendered_ = $rule->render_window();
      $render_finish_time = microtime(true);
      $render_time = ($render_finish_time - $render_start_time);
      $total_renders += $render_time;
      $average_render = ($total_renders / $rule->tick);

      $output_resu = "#$rule->tick";
      $output_resu .= " - Rule tick part took ".self::_f($tick_time)." (".self::_f($average_tick).")";
      $output_resu .= " - Rendering part took ".self::_f($render_time)." (".self::_f($average_render).")";
      $output_resu .= "\n".$rendered_;
      system('clear');
      echo $output_resu;
    }
  }

  private static function _f($value) {
    return sprintf("%.5f", round($value, 5));
  }

}

Start::run();

?>
