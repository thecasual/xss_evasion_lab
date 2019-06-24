var myList = [
    { "Name": "Rachel", "Challenge 1": "Captured", "Challenge 2": "Captured", "Challenge 3": "Captured", "Challenge 4": "Captured", "Challenge 5": "Captured", "Challenge 6": "Captured", "Challenge 7": "Captured", "Challenge 8": "Captured", "Challenge 9": "Captured", "Challenge 10": "Captured", "Challenge 11": "Captured", "Challenge 12": "Captured"}
  ];
  
  // Builds the HTML Table out of myList.
  function buildHtmlTable(selector) {
    var columns = addAllColumnHeaders(myList, selector);
  
    for (var i = 0; i < myList.length; i++) {
      var row$ = $('<tr/>');
      for (var colIndex = 0; colIndex < columns.length; colIndex++) {
        var cellValue = myList[i][columns[colIndex]];
        if (cellValue == null) cellValue = "";
        row$.append($('<td/>').html(cellValue));
      }
      $(selector).append(row$);
    }
  }
  
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