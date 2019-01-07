<?php

function readCSV($csvFile){
    $file_handle = fopen($csvFile, 'r');
    while (!feof($file_handle) ) {
        $line_of_text[] = fgetcsv($file_handle, 1024);
    }
    fclose($file_handle);
    return $line_of_text;
}
$csvFile = 'router.db';
$csv = readCSV($csvFile);

$csv = array_values($csv);
unset($csv[count($csv)-1]);

$yaml_array = yaml_parse_file('config');
$config_groups = $yaml_array["groups"];


?>
<html>
<head>
<title>Add Nodes</title>
<link href='/css/bootstrap.min.css' rel='stylesheet'>
<link href='/css/oxidized.css' rel='stylesheet'>
<link href='/css/oxidized_custom.css' rel='stylesheet'>
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script src="asset/jquery.tabledit.js"></script>
<script src="jquery-paginate.js"></script>
<style>
.container { max-width:760px;}
#myInput {
    background-image: url('/asset/images/searchicon.png'); /* Add a search icon to input */
    background-position: 10px 12px; /* Position the search icon */
    background-repeat: no-repeat; /* Do not repeat the icon image */
    width: 100%; /* Full-width */
    font-size: 16px; /* Increase font-size */
    padding: 12px 20px 12px 40px; /* Add some padding */
    border: 1px solid #ddd; /* Add a grey border */
    margin-bottom: 12px; /* Add some space below the input */

}
.page-navigation a {
  margin: 0 2px;
  display: inline-block;
  padding: 3px 5px;
  color: #ffffff;
  background-color: #484545;
  border-radius: 5px;
  text-decoration: none;
  font-weight: light;
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
}

.page-navigation a[data-selected] {
  background-color: #333;
}
</style>
<script>
$(document).ready(function(){
$('#example-2').paginate({ 
  limit: 5, // 10 elements per page
  initialPage: 1, // Start on second page
  previous: true, // Show previous button
  previousText: 'Previous page', // Change previous button text
  next: true, // Show previous button
  nextText: 'Next page', // Change next button text
});

  // Search all columns
  $('#myInput').keyup(function(){
    // Search Text
    var search = $(this).val();

    // Hide all table tbody rows
    $('table tbody tr').hide();

    // Count total search result
    var len = $('table tbody tr:not(.notfound) td:nth-child(2):contains("'+search+'")').length;

    if(len > 0){
      // Searching text in columns and show match row
      $('table tbody tr:not(.notfound) td:contains("'+search+'")').each(function(){
        $(this).closest('tr').show();
      });
    }else{
      $('.notfound').show();
    }

  });

});
</script>
<body onload="createTable()">
<nav class='navbar navbar-default navbar-static-top' role='navigation'>
<div class='container-fluid'>
<div class='navbar-header'>
<button aria-expanded='false' class='navbar-toggle collapsed' data-target='#ox-nav' data-toggle='collapse' type='button'>
<span class='sr-only'>Toggle Navigation</span>
<span class='icon-bar'></span>
<span class='icon-bar'></span>
<span class='icon-bar'></span>
</button>
<a class='navbar-brand' href='/'>
<img src="/images/oxidizing_40px.png">
</a>
</div>
<div class='collapse navbar-collapse' id='ox-nav'>
<ul class='nav navbar-nav'>
<li class=''>
<a class='navbar-link' href='/nodes'>Nodes</a>
</li>
<li>
<a href='http://rcdvc.get.netline.net.uk/add.php' title='Add Devices'>
<strong>
Add/Edit Nodes
</strong>
</a>
</li>
</ul>
</div>
</div>
</nav>
<div class="container" style="margin-top:10px;">
<h2>Edit Nodes</h2>
<input type="text" id="myInput" placeholder="Search for IP/Hostname">
<table class="table table-striped table-bordered" id="example-2">
<thead>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>IP</th>    
    <th>Model</th>
    <th>Location</th> 
  </tr>
</thead>
<tbody>
<?php
foreach ($csv as $i=>$mks){
echo "<tr><td>".$i."</td><td>".$mks[0]."</td><td>".$mks[1]."</td><td>".$mks[2]."</td><td>".$mks[3]."</td></tr>";
}
?>
</tbody>
</table>
    <p>
	<h3>Add New Nodes Below</h3>
        <input type="button" id="addRow" value="Add New Node" onclick="addRow()" />


    <!--THE CONTAINER WHERE WE'll ADD THE DYNAMIC TABLE-->
    <div id="cont"></div>

    <p><input type="button" id="bt" value="Submit Data" onclick="sumbit()" /></p>


<!--Groups-->
</body>
<script>

$('#example-2').Tabledit({
    url: 'edit.php',
    columns: {
        identifier: [0, 'id'],
        editable: [[1, 'Name'], [2, 'IP'], [3, 'Model'], [4, 'Location']]
    },
    onDraw: function() {
        console.log('onDraw()');
    },
    onSuccess: function(data, textStatus, jqXHR) {
        console.log('onSuccess(data, textStatus, jqXHR)');
        console.log(data);
        console.log(textStatus);
        console.log(jqXHR);
    },
    onFail: function(jqXHR, textStatus, errorThrown) {
        console.log('onFail(jqXHR, textStatus, errorThrown)');
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
    },
    onAlways: function() {
        console.log('onAlways()');
    },
    onAjax: function(action, serialize) {
        console.log('onAjax(action, serialize)');
        console.log(action);
        console.log(serialize);
    }
});

    // ARRAY FOR HEADER.
    var arrHead = new Array();
    arrHead = ['', 'Name', 'IP', 'Model', 'Location'];      // SIMPLY ADD OR REMOVE VALUES IN THE ARRAY FOR TABLE HEADERS.

    // FIRST CREATE A TABLE STRUCTURE BY ADDING A FEW HEADERS AND
    // ADD THE TABLE TO YOUR WEB PAGE.
    function createTable() {
        var empTable = document.createElement('table');
        empTable.setAttribute('id', 'empTable');            // SET THE TABLE ID.
        empTable.setAttribute('class', 'table table-striped table-bordered');
        var tr = empTable.insertRow(-1);
        for (var h = 0; h < arrHead.length; h++) {
            var th = document.createElement('th');          // TABLE HEADER.
            th.innerHTML = arrHead[h];
            tr.appendChild(th);
        }

        var div = document.getElementById('cont');
        div.appendChild(empTable);    // ADD THE TABLE TO YOUR WEB PAGE.
    }

    // ADD A NEW ROW TO THE TABLE.s
    function addRow() {
        var empTab = document.getElementById('empTable');

        var rowCnt = empTab.rows.length;        // GET TABLE ROW COUNT.
        var tr = empTab.insertRow(rowCnt);      // TABLE ROW.
        tr = empTab.insertRow(rowCnt);

        for (var c = 0; c < arrHead.length; c++) {
            var td = document.createElement('td');          // TABLE DEFINITION.
            td = tr.insertCell(c);

            if (c == 0) {           // FIRST COLUMN.
                // ADD A BUTTON.
                var button = document.createElement('input');

                // SET INPUT ATTRIBUTE.
                button.setAttribute('type', 'button');
                button.setAttribute('value', 'Remove');

                // ADD THE BUTTON's 'onclick' EVENT.
                button.setAttribute('onclick', 'removeRow(this)');

                td.appendChild(button);
            }
            else {
                // CREATE AND ADD TEXTBOX IN EACH CELL.
                var ele = document.createElement('input');
                ele.setAttribute('type', 'text');
                ele.setAttribute('value', '');

                td.appendChild(ele);
            }
        }
    }

    // DELETE TABLE ROW.
    function removeRow(oButton) {
        var empTab = document.getElementById('empTable');
        empTab.deleteRow(oButton.parentNode.parentNode.rowIndex);       // BUTTON -> TD -> TR.
    }

    // EXTRACT AND SUBMIT TABLE DATA.


function sumbit() {
        var myTab = document.getElementById('empTable');
        var values = new Array();
        // LOOP THROUGH EACH ROW OF THE TABLE.
        for (row = 1; row < myTab.rows.length - 1; row++) {
            for (c = 0; c < myTab.rows[row].cells.length; c++) {   // EACH CELL IN A ROW.

                var element = myTab.rows.item(row).cells[c];
                if (element.childNodes[0].getAttribute('type') == 'text') {
                    values.push(element.childNodes[0].value);
                }
            }
        }
saveTodoData(values);

}

function saveTodoData(values) {
    tempdata = [];
    values.forEach(function(value){
	tempdata.push(value.replace(/\s+/g, '-'));
    });
    //var todoJSON = JSON.stringify(values);
    var request = new XMLHttpRequest();
    var URL = "save.php?data=" + encodeURI(tempdata);
    request.open("GET", URL);
    request.setRequestHeader("Content-Type",
                             "text/plain;charset=UTF-8");
    request.send();
    location.reload();
}




</script>
</html>
