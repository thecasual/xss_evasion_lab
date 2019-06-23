$(function() {
    //Make request on each key stroke
	$('#attempt').on('input', function(e) {
        var input = $(this).val();
        var urlParams = new URLSearchParams(window.location.search);
        var challenge = urlParams.get('c');
        console.log(challenge);

		$.ajax({
            //Query replace which applies the filter server side
            //Provide challenge number to use correct filter
			url : "scrubber.php?a="+encodeURIComponent(input)+"&c="+challenge,
			cache : false,
            type : "GET",
            // If successful, update HTML "sanitizedinput" 
			success : function(response) {
				$("#sanitizedinput").html(response);
            },
            //Bad
			error : function(xhr) {
				$("#sanitizedinput").html("really bad error here");
			}
		});

	});
});

//Only runs on page load
function initfunc() {
    var urlParams = new URLSearchParams(window.location.search);
    var challenge = urlParams.get('c');
    $.ajax({

        url : "scrubber.php?c="+challenge,
        cache : false,
        type : "GET",
        success : function(response) { 
            //Parse JSON and update challenge page
            var myjson = JSON.parse(response);
            $challenge = myjson.challenge.split("challenge")[1];
            console.log(myjson);
			//Filling in challenge page
            $("#hint").html(myjson.hint);
            $("#title").html("XSS Challenge " + $challenge);
            $("#func").html("preg_replace( "+myjson.pattern+" , \;nope\; , your input)");
        },
        error : function(xhr) {
            $("#pattern").html("Unable to get pattern");
        }
    }) 
}

//For the W3 nav bar
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}

//Get pattern based on URI
window.onload = initfunc;

// If message is HACKED
function alert(message) {
    if (message === "HACKED") {
        
        //Current challenge
        var urlParams = new URLSearchParams(window.location.search);
        var challenge = urlParams.get('c');
        
        //Build pattern with current URI + $
        var re = RegExp(challenge+"$");

        //This ID contains the servers response
        var postfilter = document.getElementById("sanitizedinput").textContent;

        //Ensure URI matches the pattern for the challenge requested
        if (re.test(postfilter)) {
            console.log("Nice"); 
            return confirm("HACKED....(╯°□°)╯︵ ┻━┻");
        } else {
            //The challenge ID is different then was originally handed to the server
            return confirm("WHY YOU CHEAT....(╯°□°)╯︵ ┻━┻");
        }
        }
        
    //If alert box is present, but the string is incorrect
	return console.warn("I see an alert box....but your message is incorrect");
}