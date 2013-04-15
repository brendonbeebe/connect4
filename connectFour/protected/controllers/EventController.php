<?php

class EventController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}
    public function actionNotifyNode(){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'http://localhost');

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($ch, CURLOPT_PORT, 8000);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);

        curl_setopt($ch, CURLOPT_POST, true);

        $fields_string = "";
        $fields = array(
            "_domain"=>"rfq",
            "_name"=>"_moveMade"
        );
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');

        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

    }

    public function actionShowBoard(){

    }
    public function sendEvent($fields, $esls = null){
        if(isset($esls))
        foreach($esls as $esl){
            $url = $esl;

            //url-ify the data for the POST
            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');

            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            //execute post
            $result = curl_exec($ch);
            //close connection
            curl_close($ch);

        }
    }
    public function generateBlankBoard(){
        $blankBoard = array();
        $rowNumber = 5;

        for ($i = 0; $i < $rowNumber; $i++) {
            $blankBoard[$i] = array();
            for ($j = 0; $j < $rowNumber; $j++) {
                $blankBoard[$i][$j] = -1;
            }
        }
        return $blankBoard;
    }

    public $boardDimension = 5;
    public function validateBoard($columnPlacement,$rowPlacement,$board,$playerNumber){
        $directions = array(
            1 => array(0,1),
            2 => array(1,0),
            3 => array(1,1),
            4 => array(-1,1),
        );

        foreach($directions as $direction){
            $total = 0;
            $currentColumn = $columnPlacement;
            $currentRow = $rowPlacement;
            while($currentRow >= 0 && $currentRow < $this->boardDimension && $currentColumn >= 0 && $currentColumn < $this->boardDimension){
                //echo $currentRow . " - ".$currentColumn." ".$board[$currentColumn][$currentRow]." ".$playerNumber." $total -::::";
                if($board[$currentColumn][$currentRow] == $playerNumber){
                    $currentRow = $currentRow + $direction[0];
                    $currentColumn = $currentColumn + $direction[1];
                    $total++;
                }
                else
                    break;
            }
            $total--;
            $currentColumn = $columnPlacement;
            $currentRow = $rowPlacement;
            while($currentRow >= 0 && $currentRow < $this->boardDimension && $currentColumn >= 0 && $currentColumn < $this->boardDimension){

                if($board[$currentColumn][$currentRow] === $playerNumber){
                    //echo $currentRow . " - ".$currentColumn." ".$board[$currentColumn][$currentRow]." ".$playerNumber." $total ::::";
                    $currentRow = $currentRow + -1*$direction[0];
                    $currentColumn = $currentColumn + -1*$direction[1];
                    $total++;

                }
                else
                    break;
            }
            if($total >=4)
                return true;

        }

        return false;
    }




    private function processMakeMove(){
        $response = array();
        $response['success'] = true;
        //Find game with current player
        $playerEsl = Yii::app()->request->getParam('esl');
        $columnPlacement = Yii::app()->request->getParam('number');
        //Does game exist?
        try{

            $currentPlayer = Players::model()->find("esl=:esl",array(':esl'=>$playerEsl));
            if(!isset($currentPlayer)){
                throw new Exception("Player not found");
            }

            //$currentGame = Games::model()->find("player1_id=:playerId OR player2_id=:playerId",array(':playerId'=>$currentPlayer->id));
            $currentGame = Games::model()->find(array(
                'condition'=>"player1_id=:playerId OR player2_id=:playerId",
                'params'=>array(':playerId'=>$currentPlayer->id),
                'order'=>'id DESC'
            ));
            if(!isset($currentGame)){
                throw new Exception("Game not found.");
            }
            //Is game active?
            if($currentGame->active == 'N'){
                throw new Exception("Game has either not started or has already ended.");
            }

            //Is it this players turn?
            $playerNumber = -1;
            if($currentGame->whosTurn == 1 && $currentGame->player1_id == $currentPlayer->id){
                $playerNumber = 0;
            } else if($currentGame->whosTurn == 2 && $currentGame->player2_id == $currentPlayer->id){
                $playerNumber = 1;
            } else {
                //$playerNumber = 0;
                throw new Exception("Not players turn");
            }

            //Make move
            if($columnPlacement > $this->boardDimension || $columnPlacement < 0){
                throw new Exception("Move not valid");
            }
            //Calculate new board
            $board = $currentGame->board;
            $board = json_decode($board);


            $rowPlacement = $this->boardDimension-1;
            for($i = 0; $i < $this->boardDimension; $i++){
                if($board[$columnPlacement][$i] != -1){
                    $rowPlacement = $i-1;
                    break;
                }
            }

            //Assign player token to spot
            if($rowPlacement == -1){
                throw new Exception("Invalid move.");
            } else {
                $board[$columnPlacement][$rowPlacement] = $playerNumber;
            }

            //Check if player won
            $gameOver = $this->validateBoard($columnPlacement,$rowPlacement,$board,$playerNumber);




            $currentGame->board = json_encode($board);

            if($gameOver){
                $currentGame->active = 'N';
                $player1Victory = "";
                if($currentGame->whosTurn == 1 && $currentGame->player1_id == $currentPlayer->id){
                    //Player 1 won
                    $player1Victory = 1;
                } else {
                    //Player 2 won
                    $player1Victory = -1;

                }
                if(!$currentGame->save()){
                    throw new Exception($currentGame->errors);
                }
                //Send Events
                $esls = array($currentGame->player1->esl);
                $fields = array(
                    "_domain"=>"rfq",
                    "_name"=>"_gameEnded",
                    "victory"=>$player1Victory
                );
                $this->sendEvent($fields,$esls);

                $esls = array($currentGame->player2->esl);
                $fields = array(
                    "_domain"=>"rfq",
                    "_name"=>"_gameEnded",
                    "victory"=>-1 *$player1Victory
                );
                $this->sendEvent($fields,$esls);


                $response['success'] = true;
                $response['message'] = "Game Over.";
                //$this->actionNotifyNode();
                return $response;

            } else {
                $boardFull = true;
                for($i=0;$i <  $this->boardDimension;$i++){
                    if($board[$i][0] == -1){
                        $boardFull = false;
                        break;
                    }
                }
                if($boardFull == true){
                    $currentGame->active = 'N';
                    if(!$currentGame->save()){
                        throw new Exception($currentGame->errors);
                    }
                    $esls = array($currentGame->player2->esl,$currentGame->player1->esl);
                    $fields = array(
                        "_domain"=>"rfq",
                        "_name"=>"_gameEnded",
                        "victory"=>-1
                    );
                    $this->sendEvent($fields,$esls);


                    $response['success'] = true;
                    $response['message'] = "Game Over.";
                    //$this->actionNotifyNode();
                    return $response;
                } else {
                    //Change players turn
                    if($playerNumber == 0)
                        $currentGame->whosTurn = 2;
                    else
                        $currentGame->whosTurn = 1;
                }

            }





            if(!$currentGame->save()){
                throw new Exception($currentGame->errors);
            }





        } catch (Exception $e){
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return $response;
        }

        $fields = array(
            "_domain"=>"rfq",
            "_name"=>"_moveMade",
            "board"=>$currentGame->board
        );
        $esls = array(
            1=>$currentGame->player1->esl,
            2=>$currentGame->player2->esl
        );
        $this->sendEvent($fields,$esls);

        $response['success'] = true;
        $response['message'] = "Move Made.";

        //$this->actionNotifyNode();
        return $response;
    }
    private function processStartGame(){
        $response = array();
        $response['success'] = true;

        $newEsl = Yii::app()->request->getParam('esl');
        $newPlayer = Players::model()->find("esl=:esl",array(':esl'=>$newEsl));

        try{
//Check if players esl already exists
            if(isset($newPlayer)){
                //Is player currently playing a game?
                //$currentGame = Games::model()->find("(player1_id=:playerId OR player2_id=:playerId) AND active='Y' ",array(':playerId'=>$newPlayer->id));
                $currentGame = Games::model()->find(array(
                    'condition'=>"(player1_id=:playerId OR player2_id=:playerId) AND active='Y' ",
                    'params'=>array(':playerId'=>$newPlayer->id),
                    'order'=>'id DESC'
                ));
                if($newPlayer->waiting == 'N' && isset($currentGame)){
                    //Throw error
                    throw new Exception("Player is already playing a game and cannot start a new one.");

                } else if($newPlayer->waiting == 'N' && !isset($currentGame)){
                    $newPlayer->waiting = 'Y';
                    $newPlayer->save();
                    //Throw error
                    //throw new Exception("Player is already playing a game and cannot start a new one.");

                } else {
                    //No error but no need to do any more processing
                    //throw new Exception("Player is already waiting for game.");

                }
            } else {
                $newPlayer = new Players;
                $newPlayer->esl = $newEsl;
                $newPlayer->waiting = "N";
                if(!$newPlayer->save()){
                    //Throw error
                    throw new Exception( $newPlayer->errors);

                }
            }


            //If there is a player waiting then start game
            $waiting_player = Players::model()->find("waiting = 'Y' && id !=".$newPlayer->id);
            //Create game with most recent player as player1
            if( isset($waiting_player)){
                $game = new Games;
                $game->player1_id =$newPlayer->id;
                $game->player2_id = $waiting_player->id;
                $game->whosTurn = 1;
                $game->active = 'Y';
                $game->board = json_encode($this->generateBlankBoard());
                $game->save();
                $newPlayer->waiting = "N";
                $newPlayer->save();
                $waiting_player->waiting = "N";
                $waiting_player->save();
            } else {
                $newPlayer->waiting = "Y";
                $newPlayer->save();
                $response['success'] = false;
                $response['message'] = "Game request sent we will let you know when we find a player.";
                return $response;
            }
        } catch (Exception $e){
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return $response;
        }


        //Send to both players
        $fields = array(
            "_domain"=>"rfq",
            "_name"=>"_gameStarted",
        );
        $esls = array(
            1=>$game->player1->esl,
            2=>$game->player2->esl
        );
        $this->sendEvent($fields,$esls);



        //Send to player1
        $fields = array(
            "_domain"=>"rfq",
            "_name"=>"_moveMade",
            "board"=>$game->board
        );
        $esls = array(
            1=>$game->player1->esl,
        );
        $this->sendEvent($fields,$esls);
        $response['success'] = true;
        $response['message'] = "Game started.";
        return $response;
    }
    private function processBoardRequest(){
        $response = array();
        $response['success'] = true;

        //Find game with current player
        $playerEsl = Yii::app()->request->getParam('esl');

        try{

            $currentPlayer = Players::model()->find("esl=:esl",array(':esl'=>$playerEsl));
            if(!isset($currentPlayer)){
                throw new Exception("Player not found");
            }

            $currentGame = Games::model()->find(array(
                'condition'=>"player1_id=:playerId OR player2_id=:playerId",
                'params'=>array(':playerId'=>$currentPlayer->id),
                'order'=>'id DESC'
            ));
            if(!isset($currentGame)){
                throw new Exception("Game not found.");
            }


            if($currentGame->player2_id == $currentPlayer->id){
                $whichPlayer = 1;
            } else
                $whichPlayer = 0;
            //Is it this players turn?
            $isMyTurn = 'N';
            if($currentGame->whosTurn == 1 && $currentGame->player1_id == $currentPlayer->id){
                $isMyTurn = 'Y';
            } else if($currentGame->whosTurn == 2 && $currentGame->player2_id == $currentPlayer->id){
                $isMyTurn = 'Y';
            }



            $board = json_decode($currentGame->board);

            //echo json_encode($board[1]);
            $response['player'] = $whichPlayer;
            $response['isMyTurn'] = $isMyTurn;
            $response['board']=$board;
            $response['gameOver']= ($currentGame->active == 'N');




        } catch (Exception $e){
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return $response;
        }
        return $response;
    }

    public function actionConsume(){

        //contact is a Yii model:
        //this is for responding to the client:
        $response = array();
        $response['success'] = true;
        if( Yii::app()->request->getParam('_domain') != null){
            /*
             * Process Delivery Ready Event
             */
            if(Yii::app()->request->getParam('_name') == "_makeMove"){
                $response = $this->processMakeMove();
            } else if(Yii::app()->request->getParam('_name') == "_startGame"){
                $response = $this->processStartGame();

            } else if(Yii::app()->request->getParam('_name') == "_sendChat"){




            } else if(Yii::app()->request->getParam('_name') == "_boardRequest"){
                $response = $this->processBoardRequest();
            }

            //save model, if that fails, get its validation errors:
            if(isset($model)){
                if ($model->save() === false) {
                    $response['success'] = false;
                    $response['message'] = $model->errors;
                } else {
                    $response['success'] = true;
                    $response['event'] = $model;
                }
            }



            //respond with json content type:
            header('Content-type:application/json');

            //encode the response as json:
            echo CJSON::encode($response);

            //use exit() if in debug mode and don't want to return debug output
        }

        exit();
    }

}