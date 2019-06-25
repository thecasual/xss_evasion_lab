var myjson = {};

function updatescore() { 
  $.ajax({
    url : "scrubber.php?getscore=1337",
    cache : false,
    type : "GET",
    success : function(response) { 
      
      myjson = response;
      //console.log(myjson +" " + response);
        //Parse JSON and update challenge page
        //$myjson = JSON.parse(response);
    },
    error : function(xhr) {
        console.log("Error getting scores...");
    }
  })
  
}

updatescore();
console.log(myjson)
