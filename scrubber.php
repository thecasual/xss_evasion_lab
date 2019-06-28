<?php header('X-XSS-Protection: 0');
//Get query parameters
$qparams = array();
parse_str($_SERVER['QUERY_STRING'], $qparams);

$challenge = $qparams['c'];
$attempt = $qparams['a'];
$replacement = '\;nope\;';


//Update score
$score = [];


if(isset($qparams['newuser'])) {
    $user = $_COOKIE['username'];
    $tempread = file_get_contents('score.json');
    $json_read = json_decode($tempread, true);

    for ($i = 0 ; $i < count($json_read); $i++) {       
        foreach ($json_read[$i] as $key => $value){
            echo $key . $value;
            if($key == "user" && $value == $user){
                exit("user already exists");
            }
        }
    }
    $json_read[] = array( "user" => $user);
    file_put_contents("score.json", json_encode($json_read));
}



if(isset($_GET['getscore'])) {
    if(isset($_COOKIE['username'])){
        $user = $_COOKIE['username'];
        
        foreach ($_COOKIE as $key=>$val) {
            //echo $key;
            if (strpos($key, 'challenge') !== false) {
                
                //Completed challenges found
                //echo $key;
                
                

                //Get current sscores
                $tempread = file_get_contents('score.json');
                $json_read = json_decode($tempread, true);
                //echo json_encode($json_read);

                
                $challengename = preg_replace("/challenge/", "Challenge ", $key );
                for ($i = 0 ; $i < count($json_read); $i++) {
                    
                    foreach ($json_read[$i] as $key => $value){
                        

                        if($key == "user" && $value == $user){
                            $userfound = True;
                            echo "user found" . "<br>";
                            if(!empty($json_read[$i][$challengename])){
                                echo "flag exists";
                            } else {
				//webhook here
				$msg = "Flag Captured!! " . $challengename . " Completed By " . $user . "\n\nJOIN HERE: http://host\n\nSCOREBOARD: http://host/score";
				$url = "http://www.example.com";

				$data = array("text" => $msg);

				$options = array(
				    'http' => array(
				      'method'  => 'POST',
				      'content' => json_encode( $data ),
				      'header'=>  "Content-Type: application/json\r\n" .
	      	                  "Accept: application/json\r\n"
       					)
				  );
  $context  = stream_context_create( $options );
  $result = file_get_contents( $url, false, $context );
  $response = json_decode( $result );
	

				echo $msg;
                                echo "no flag";
                                $json_read[$i][$challengename] = "Captured";
                                echo json_encode($json_read);
                                file_put_contents("score.json", json_encode($json_read));
    
    

                                #$json_read = array_replace($json_read[$i], $m);

                            }
                        
                        }   
                    }
                }
                

            }
        }
    }
}

//Add new challenges here!
$qparams['c'];
if(isset($qparams['c'])) {
    
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
        $hint = "No more script tag";
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
        $pattern = "/(alert|#|&|\+|src|on)/i";

    } elseif ($challenge == "challenge8") {
        //Decimal NCR
        $hint = "You have a fraction of a chance";
        $pattern = '/(alert|x0|\\\\|\+|eval|atob|on)/i';

    } elseif ($challenge == "challenge9") {
        //Hex
        $hint = "Can you encode?";
        $pattern = '/(on|top|alert|#[1-9])/i';

    } elseif ($challenge == "challenge10") {
        //From CharCode
        $hint = "Stahp evaling everything";
        $pattern = '/(eval|top|#|&|\\\\)/i';

    } elseif ($challenge == "challenge11") {
        //atob
        $hint = "Can you trigger an alert box still?";
        $pattern = '/(alert|#|src|&|\\\\|string|fromCharCode)/i';

    } elseif ($challenge == "challenge12") {
        //toString
        $hint = "I won't let you src javascript here";
        $pattern = '/(alert|#|&|\\\\|eval|fromCharCode|on|x0|\+|img|svg)/i';

    } elseif (isset($_GET['getscore'])) {
        //pass
    } else {
        //Challenge doesn't exists
        throw new exception('WUT! Challenge doesn\'t exists');
    }
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
