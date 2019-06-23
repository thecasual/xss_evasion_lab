<?php header('X-XSS-Protection: 0');
//Get query parameters
$qparams = array();
parse_str($_SERVER['QUERY_STRING'], $qparams);

$challenge = $qparams['c'];
$attempt = $qparams['a'];
$replacement = '\;nope\;';
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

} else {
    //Challenge doesn't exists
    throw new exception('WUT! Challenge doesn\'t exists');
}

if(isset($_GET['a'])){
    //If "a" exists then apply the filter and send back
    echo '<p>' . preg_replace($pattern, $replacement, $attempt) . '</p>';
    echo $challenge;
}

if(!isset($_GET['a'])) {
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