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

//"Borrowed" from here https://www.w3schools.com/js/js_cookies.asp
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }


function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
}

function checkCookie() {
    var username = getCookie("username");
    if (username != "") {
     console.log("Welcome " + username);
    } else {
      username = prompt("Please enter your name:", "");
      if (username != "" && username != null) {
        setCookie("username", username, 365);
      }
    }
  }

function initfunc() {
    console.log(document.cookie);
    //checkCookie();
    var urlParams = new URLSearchParams(window.location.search);
    var challenge = urlParams.get('c');
    $.ajax({
        url : "scrubber.php?c="+challenge,
        cache : false,
        type : "GET",
        success : function(response) { 
            //Parse JSON and update challenge page
            console.log(JSON.parse(response));
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
            setCookie(challenge, postfilter, 12345);
            return confirm("HACKED....(╯°□°)╯︵ ┻━┻");
        } else {
            //The challenge ID is different then was originally handed to the server
            //AKA catch people who don't read source code
            return confirm("WHY YOU CHEAT....(╯°□°)╯︵ ┻━┻");
        }
    }
        
    //If alert box is present, but the string is incorrect
	return console.warn("I see an alert box....but your message is incorrect");
}