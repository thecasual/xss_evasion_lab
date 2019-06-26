<?php header('X-XSS-Protection: 0');
//Get query parameters
$qparams = array();
parse_str($_SERVER['QUERY_STRING'], $qparams);

$challenge = $qparams['c'];
$attempt = $qparams['a'];
$replacement = '\;nope\;';


//Update score
$score = [];


if(isset($_GET['getscore'])) {
    if(isset($_COOKIE['username'])){
        $user = $_COOKIE['username'];
        foreach ($_COOKIE as $key=>$val) {
            //echo $key;
            if (strpos($key, 'challenge') !== false) {
                //Completed challenges found
                
                $challengenum = preg_replace("/[^0-9]/", "", $key );
                $challengepoints = (string)($challengenum) . "0";


                //temp add to json
                $tempread = file_get_contents('score.json');
                $json_read = json_decode($tempread, true);
                //$json_read = json_decode($tempread, true);
                //$json_read[] = array("user" => "tim", "7" => "Captured");
                //file_put_contents("score.json", json_encode($json_read));

                //get current score store as json
                echo json_encode($json_read);


                //check if user is already in json
                /*if($curscore['user'] = $user) {
                    echo "user found in json";
                    $found = False;
                    foreach ($curscore as $key => $value) {
                        if ($value[$challengenum] == "Captured") { 
                            echo "already captured" . $challengenum;
                            $found = True;
                        }
                    }
                    if($found == False);
                        echo "not found..adding";
                        $curscore[] = ['name' => $user, $challengenum => "Captured"];
                        $json = json_encode($curscore);
                        file_put_contents("score.json",$json);
                }
                */

                
                
                
                
                //print_r($json);

                #$curscore = json_decode(file_get_contents('score.json'), true);
                #$curscore[] = ["user"=>"test4", $challengenum=>"Captured"];
                //$myscore = array ("user"=>"test3", $challengenum=>"Captured");
                //array_push($curscore, $myscore);
                
                //file_put_contents("score.json",json_encode($myscore));

                //if($curscore['user'] = "testo") {
                //    echo "test";
                //}


                //$myscore = array ("user"=>$user, $challengenum=>"Captured");
                //file_put_contents("score.json",json_encode($curscore));


                //$curscore = json_decode(file_get_contents('score.json'), true);

                //echo json_encode($curscore);


               
                //echo json_encode($curscore);
                //echo $curscore;
                
                //echo $user . $challengenum . $challengepoints;

                //echo "test";
                //echo $user . "challenge" . $challengenum;
                //$score = json_encode(array($user => array('challenge' => "$challengenum")));
            }
        }
    }
}
    //fwrite($scorefile, $score);
    //fclose($scorefile);
    //$scorefile = fopen("scorefile.txt", "r");
    //$scorefile = fopen("scorefile.txt", "w");
    //echo fread($scorefile, filesize("scorefile.txt"));



//Add new challenges here!
if ($challenge == "challenge1") {
    $hint = htmlspecialchars("Gimmie dat <script>alert(\"HACKED\")</script>", ENT_QUOTES, 'UTF-8');
    $pattern = '/ezmode/i';

} elseif ($challenge == "challenge2") {
    $hint = "Does case matter?";
    $pattern = '/<script>/';

} elseif ($challenge == "challenge3") {
    $hint = "Fixed the case issue! Well, can you have spaces?? You tell me.";
    $pattern = '/<script>/i';

} elseif ($challenge == "challenge4") {
    $hint = "No more script tag. Can you run javascript in images?";
    $pattern = '/script/i';

} elseif ($challenge == "challenge5") {
    //SVG XSS
    $hint = "XSS in images? Gotta fix that! What is this silly graphical tag for drawing images?";
    $pattern = '/(script|img)/i';

} elseif ($challenge == "challenge6") {
    //Adding characters to form a string
    $hint = "Enough with this...No more alert ing.";
    $pattern = '/(alert|#|\\\\|&|atob|src)/i';

} elseif ($challenge == "challenge7") {
    //Escape Characters
    $hint = "Think outside of the jail\cell";
    $pattern = "/(alert|#|&|\+|src)/i";

} elseif ($challenge == "challenge8") {
    //Decimal NCR
    $hint = "You have a fraction of a chance";
    $pattern = '/(alert|x0|\\\\|\+|eval|atob)/i';

} elseif ($challenge == "challenge9") {
    //Hex
    $hint = "Can you encode?";
    $pattern = '/(alert|#[1-9])/i';

} elseif ($challenge == "challenge10") {
    //From CharCode
    $hint = "Stahp evaling everything";
    $pattern = '/(eval|#|&|\\\\)/i';

} elseif ($challenge == "challenge11") {
    //atob
    $hint = "Can you trigger an alert box still?";
    $pattern = '/(alert|#|&|\\\\|string|fromCharCode)/i';

} elseif ($challenge == "challenge12") {
    //toString
    $hint = "Can't run javascript here";
    $pattern = '/(alert|#|&|\\\\|eval|fromCharCode|on|x0|\+|img|svg)/i';

} elseif (isset($_GET['getscore'])) {
    //pass
} else {
    //Challenge doesn't exists
    throw new exception('WUT! Challenge doesn\'t exists');
}

if(isset($_GET['a'])){
    //If "a" exists then apply the filter and send back
    echo '<p>' . preg_replace($pattern, $replacement, $attempt) . '</p>';
    
    echo '<br>Filter: ' . $challenge;
}

if(!isset($_GET['a']) and !isset($_GET['getscore'])) {
    if(isset($_GET['c'])) {
    //Dynamically update page
    //Init
        $myjson = (object) [
            'challenge' => "XSS $challenge",
            'hint' => $hint,
            'pattern' => htmlspecialchars($pattern),
            'replacement' => $replace
        ];
        echo json_encode($myjson);
    }
}
?>