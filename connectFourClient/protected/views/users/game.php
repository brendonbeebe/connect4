<script>
    function makeMove($column){
        console.log("hey");
        $.ajax({
            url: "<?php echo Yii::app()->createUrl('users/makeMove');?>?column="+$column
        })
    }

</script>

<script src="http://code.jquery.com/jquery-1.6.2.min.js"></script>
<script src="http://beebe.asuscomm.com:8000/socket.io/socket.io.js"></script>
      <script>
         var name = '';
         var socket = io.connect('http://beebe.asuscomm.com:8000');

         // at document read (runs only ones).
         $(document).ready(function(){
             $("#drop").unbind().live('click',function(){

                 //notice the use of the `var` keyword to keep variables local
                 var original_url = this.href;

                 //do the AJAX request to your server-side script
                 $.ajax({
                     url     : original_url,
                     type    : 'get',
                     success : function (serverResponse) {
                         //successfull callback, forward user to original_url
                         //window.location = original_url;
                     },
                     error   : function () {
                         //an error occured, you probably just want to forward the user to their destination
                         //window.location = original_url;
                     }
                 });

                 //returning false inside a jQuery Event Handler is the same as calling `event.preventDefault()` and `event.stopPropagation()`
                 return false;
             });
            // send the name to the server, and the server's
            // register wait will recieve this.
            //socket.emit('register', 'crazy' );
         });

         // listen for chat event and recieve data
         socket.on('moveMade', function () {

            // print data (jquery thing)
            //$("p#data_recieved").append("WHATTTT");
             $("div#table").html("");
             $.get("/connectFourclient/index.php/users/CreateBoard",
                 function(data) {
                     $("div#table").html(data);
                 });
			//setTimeout("location.reload(true);",0);

         });
      </script>
<?php
echo "<div id='table'>";
foreach(Yii::app()->user->getFlashes() as $key => $message) {
    echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
}
?>
<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

//Start Game Button
if(isset($board)){
    $rows = count($board[0]);
    $columns = count($board);
    if($player == 0)
        $piece = "X";
    else
        $piece = "O";
    //echo "<table><tr><td  class='$piece'>My Color</td></tr></table>";

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
//Make Move button

?>
