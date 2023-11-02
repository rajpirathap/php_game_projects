<?php
session_start();
/*
$page = $_SERVER['PHP_SELF'];
$sec = "1";
*/
// Define grid size?
$grid_dimension = 15;
$_starting_cells = [];
$cells = [];
$total_alive = 0;
$same_grid = false;

// Reset
if (isset($_GET['new'])) {
    unset($_SESSION['cells']);
}

// Set the grid
if (isset($_SESSION['cells'])) {
    $_starting_cells = $_SESSION['cells'];
} else {
    // Initial grid
    // The rows
    for ($ci = 1; $ci <= $grid_dimension; $ci++) {
        // The columns
        for ($cr = 1; $cr <= $grid_dimension; $cr++) {
            //only about 30% of the spaces as occupied
            $_starting_cells[$ci][$cr] = (rand(0, 10) > 7) ? 1 : 0;
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <style>
        table#game_grid {
            border-collapse: collapse;
        }

        table#game_grid td {
            border: 1px solid #999;
            width: 30px;
            height: 30px;
        }

        .alive { /* i have added brower capability */
            background: #3f4c6b; /* Old browsers */
            background: -moz-linear-gradient(top, #3f4c6b 0%, #545a68 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #3f4c6b), color-stop(100%, #545a68)); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top, #3f4c6b 0%, #545a68 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top, #3f4c6b 0%, #545a68 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top, #3f4c6b 0%, #545a68 100%); /* IE10+ */
            background: linear-gradient(to bottom, #3f4c6b 0%, #545a68 100%); /* W3C */
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h3 class="text-muted">Game of Life</h3>
    </div>
    <div class="row marketing">
    </div>
    <div class="row marketing">
        <div class="col-lg-12">
            <table id="game_grid">
		<?php $world = new World(); ?>
                <?php for ($row = 1; $row <= $grid_dimension; $row++): ?>
                    <tr>
                        <?php for ($col = 1; $col <= $grid_dimension; $col++): ?>
                            <?php 

				$cells[$row][$col] = $world->checkAroundCells($_starting_cells, $row, $col) 
				?>
                            <?php $total_alive += $_starting_cells[$row][$col] ?>
                            <td class="<?php echo ($_starting_cells[$row][$col]) ? 'alive' : '' ?>">&nbsp;</td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>
                <?php if (isset($_SESSION['cells']) and $_SESSION['cells'] == $cells): ?>
                    <?php $same_grid = true; ?>
                <?php endif; ?>
                <?php $_SESSION['cells'] = $cells; ?>
            </table>
            <?php if ($same_grid): ?>
                <hr>
                <h2>Nothing to do.</h2>
                <?php unset($_SESSION['cells']) ?>
            <?php endif; ?>
            <?php if ($total_alive == 0): ?>
                <hr>
                <h2>Everyone has Died.</h2>
                <?php unset($_SESSION['cells']) ?>
            <?php endif; ?>
            <hr>
            <a href="./?new=true" class="btn btn-default">Clear Cells</a> <a href="./" class="btn btn-success">Next Pattern</a>
        </div>
    </div>

</div>
</div>

</body>
</html>

 <?php
class World
{
    function checkAroundCells($cells, $cell_row, $cell_col) /* check rules */
    {
        $total_cells = $this->addCellsToGrid($cells, $cell_row, $cell_col);
        if (($cells[$cell_row][$cell_col] == 1 and $total_cells == 2) or $total_cells == 3) {
            return 1;
        }
        
        return 0;
    }
    
    function addCellsToGrid($cells, $cell_row, $cell_col)
    {
        $add_new_cells = 0;
        $add_new_cells += $this->getCellValue($cells, $cell_row - 1, $cell_col - 1);
        $add_new_cells += $this->getCellValue($cells, $cell_row - 1, $cell_col);
        $add_new_cells += $this->getCellValue($cells, $cell_row - 1, $cell_col + 1);
        $add_new_cells += $this->getCellValue($cells, $cell_row, $cell_col - 1);
        $add_new_cells += $this->getCellValue($cells, $cell_row, $cell_col + 1);
        $add_new_cells += $this->getCellValue($cells, $cell_row + 1, $cell_col - 1);
        $add_new_cells += $this->getCellValue($cells, $cell_row + 1, $cell_col);
        $add_new_cells += $this->getCellValue($cells, $cell_row + 1, $cell_col + 1);
        
        return $add_new_cells;
    }
    
    function getCellValue($cells, $row, $col)
    {
        return isset($cells[$row][$col]) ? $cells[$row][$col] : 0;
    }
}
?> 
