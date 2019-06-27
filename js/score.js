$.ajax({
  url : "/scrubber.php?newuser=1337",
  cache : false,
  type : "GET",
  success : function(response) { 
      console.log("getting score");
  },
  error : function(xhr) {
      console.log("really bad error here...");
  }
})  

$.ajax({
  url : "/scrubber.php?getscore=1337",
  cache : false,
  type : "GET",
  success : function(response) { 
      console.log("getting score");
  },
  error : function(xhr) {
      console.log("really bad error here...");
  }
}) 

// Builds the HTML Table out of myList.
function buildHtmlTable(selector) {

  //This call is fast which requires a refresh
  $.ajax({
    url : "score.json",
    cache : false,
    type : "GET",
    success : function(response) {
        myList = response;
  //Filling in challenge page
    


  var columns = addAllColumnHeaders(myList, selector);
  console.log(columns);

  for (var i = 0; i < myList.length; i++) {
    var row$ = $('<tr/>');
    for (var colIndex = 0; colIndex < columns.length; colIndex++) {
      var cellValue = myList[i][columns[colIndex]];
      console.log(myList[i]);
      if (cellValue == null) cellValue = "";
      row$.append($('<td/>').html(cellValue));
    }
    $(selector).append(row$);
  }},
  error : function(xhr) {
      console.log("really bad error here...");
  }
})
}

// Adds a header row to the table and returns the set of columns.
// Need to do union of keys from all records as some records may not contain
// all records.
function addAllColumnHeaders(myList, selector) {
  var columnSet = [];
  var headerTr$ = $('<tr/>');

  for (var i = 0; i < myList.length; i++) {
    var rowHash = myList[i];
    for (var key in rowHash) {
      if ($.inArray(key, columnSet) == -1) {
        columnSet.push(key);
        headerTr$.append($('<th/>').html(key));
      }
    }
  }
  $(selector).append(headerTr$);

  return columnSet;
}



