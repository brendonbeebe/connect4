<?php
/**
 * Created by JetBrains PhpStorm.
 * User: beebe
 * Date: 4/8/13
 * Time: 9:46 PM
 * To change this template use File | Settings | File Templates.
 */
class FSCore{

    public static function sendEvent($fields, $esls = null){
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
                echo $result;
                //close connection
                curl_close($ch);

            }
    }

    public static function sendEventToServer($fields){

        $url = Yii::app()->params['rootServerEsl'];

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
        return $result;
    }
}