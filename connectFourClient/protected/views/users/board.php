
<?php
foreach(Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
}
?>
<?php

//Start Game Button
if(isset($board)){
    $rows = count($board[0]);
    $columns = count($board);
    if($player == 0)
        $piece = "X";
    else
        $piece = "O";
    //echo "<div id='table'><table><tr><td  class='$piece'>My Color</td></tr></table>";
    if($myTurn == 'Y'){
        echo "<table><tr><td  class='$piece'>Your Turn</td></tr></table>";
    }
    echo "<table class='game'><thead><tr>";
//print header
    for($j = 0;$j<$columns;$j++){
        echo "<th><a href='".Yii::app()->createUrl('users/makeMove')."?column=".$j. "' id='drop'>Drop</a></th>";
    }
    echo "</tr></thead>";

    for($i = 0;$i<$rows;$i++){
        echo "<tr>";
        for($j = 0;$j<$columns;$j++){
            if($board[$j][$i] == -1)
                $piece = "";
            else if($board[$j][$i] == 0)
                $piece = "X";
            else if($board[$j][$i] == 1)
                $piece = "O";
            echo "<td class='$piece'>".$piece."</td>";
        }
        echo "</tr>";
    }
    echo "</table></div>";

}


?>
