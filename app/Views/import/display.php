
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title center">Router Details</h3>
            
        </div>
        <div class="card-body">
            <table class="mdl-js-data-table center" width="100%" id="routerDetails">
                <thead>
                    <tr>
                        <th>SapID</th>
                        <th>Hostname</th>
                        <th>Loopback</th>
                        <th>Mac Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($result as $row) : ?>
      <tr>
        <td><?= $row['sap_id']; ?></td>
        <td><?= $row['hostname']; ?></td>
        <td><?= $row['loopback']; ?></td>
        <td><?= $row['mac_addr']; ?></td>
        <td><button class="btn-delete mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Delete</button></td>
      </tr>
    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <br/>
    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" id="submitBtn">Confirm</button>
</section>

<style>
    td {
  text-align: center;
}
    </style>
<script>
  document.getElementById("submitBtn").addEventListener("click", function(e) {
  e.preventDefault();
  var rows = $('#routerDetails tbody tr');
  
  rowsValidate = new Array();
  for(i=1;i<=rows.length;i++){
  var getId = document.getElementById("routerDetails");
  var getTagName = getId.getElementsByTagName("tr");
  var getTh = getTagName[i];
  var getClass = getTh.className;
  rowsValidate[i] = getClass;
  }
  if(rowsValidate.includes("red") || rowsValidate.includes("gray")){
    alert("Please fix the errors!");
  } else {
    var data = [];
    $("#routerDetails tr").each(function() {
    var row = [];
    $(this).find("td").each(function() {
        row.push($(this).text());
    });
    data.push(row);
    });

    $.ajax({
    type: "POST",
    url: "<?= base_url('importrouterdata/saveData') ?>",
    data: { data: data },
    success: function(response) {
        console.log(response);
    }
    });
  }
  
});    

$(document).ready(function() {
  $("#routerDetails").on("click", ".btn-delete", function() {
    $(this).closest("tr").remove();
  });
});

$(document).ready(function(){
   
  // get all the rows in the table
  var rows = $('#routerDetails tbody tr');
    
  // loop through each row
  rows.each(function() {
    var currentRow = $(this);
    var sapid = currentRow.find('td:eq(0)').text();
    var hostname = currentRow.find('td:eq(1)').text();
    var loopback = currentRow.find('td:eq(2)').text();
    var macAddress = currentRow.find('td:eq(3)').text();

    // check if this row is a duplicate
    rows.not(currentRow).each(function() {
      var otherRow = $(this);
      var otherSapid = otherRow.find('td:eq(0)').text();
      var otherHostname = otherRow.find('td:eq(1)').text();
      var otherLoopback = otherRow.find('td:eq(2)').text();
      var otherMacAddress = otherRow.find('td:eq(3)').text();

      if (sapid === otherSapid && hostname === otherHostname && loopback === otherLoopback && macAddress === otherMacAddress) {
        currentRow.css('background-color', '#ccc');
        currentRow.attr('title', 'Duplicate Entry');
        currentRow.addClass('gray');
        
      }

      if (!sapid || !hostname || !loopback || !macAddress) {
      currentRow.css('background-color', '#fdd');
      currentRow.attr('title', 'Missing Field');
      currentRow.addClass('red');
      
    }

    // define the regex patterns for each field
    var sapidPattern = /^\d{18}$/;
    var hostnamePattern = /^\w{1,14}$/;
    var loopbackPattern = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/;
    var macAddressPattern = /^\w{2}:\w{2}:\w{2}:\w{2}:\w{2}:\w{2}$/;

    // check if any of the fields do not match the format
    if (!sapid.match(sapidPattern) || !hostname.match(hostnamePattern) ||
      !loopback.match(loopbackPattern) || !macAddress.match(macAddressPattern)) {
      currentRow.css('background-color', '#fdd');
      currentRow.attr('title', 'Incorrect Format');
      currentRow.addClass('red');
      
    }
    });
  });
});

$("td").dblclick(function() {
    var originalContent = $(this).text();

    $(this).addClass("cellEditing");
    $(this).html("<input type='text' value='" + originalContent + "' />");
    $(this).children().first().focus();

    $(this).children().first().keypress(function(e) {
      if (e.which == 13) {
        var newContent = $(this).val();
        $(this).parent().text(newContent);
        $(this).parent().removeClass("cellEditing");

        // loop through each row
        var newrows = $('#routerDetails tbody tr');
        
        newrows.each(function() {
    var updatedRow = $(this);
    var sapid = updatedRow.find('td:eq(0)').text();
    var hostname = updatedRow.find('td:eq(1)').text();
    var loopback = updatedRow.find('td:eq(2)').text();
    var macAddress = updatedRow.find('td:eq(3)').text();
    
    // check if this row is a duplicate
    newrows.not(updatedRow).each(function() {
      var otherRow = $(this);
      var otherSapid = otherRow.find('td:eq(0)').text();
      var otherHostname = otherRow.find('td:eq(1)').text();
      var otherLoopback = otherRow.find('td:eq(2)').text();
      var otherMacAddress = otherRow.find('td:eq(3)').text();

      if (sapid === otherSapid && hostname === otherHostname && loopback === otherLoopback && macAddress === otherMacAddress) {
        console.log(sapid);
        updatedRow.css('background-color', '#ccc');
        updatedRow.attr('title', 'Duplicate Entry');
        updatedRow.addClass('gray');
        
      } else {
        updatedRow.css('background-color', '');
        updatedRow.removeClass('gray');
        
    }

      if (!sapid || !hostname || !loopback || !macAddress) {
        updatedRow.css('background-color', '#fdd');
        updatedRow.attr('title', 'Missing Field');
        updatedRow.addClass('red');
        
    }
    else {
        updatedRow.css('background-color', '');
        updatedRow.removeClass('red');
        
    }

    // define the regex patterns for each field
    var sapidPattern = /^\d{18}$/;
    var hostnamePattern = /^\w{1,14}$/;
    var loopbackPattern = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/;
    var macAddressPattern = /^\w{2}:\w{2}:\w{2}:\w{2}:\w{2}:\w{2}$/;

    // check if any of the fields do not match the format
    if (!sapid.match(sapidPattern) || !hostname.match(hostnamePattern) ||
      !loopback.match(loopbackPattern) || !macAddress.match(macAddressPattern)) {

        updatedRow.css('background-color', '#fdd');
        updatedRow.attr('title', 'Incorrect Format');
        updatedRow.addClass('red');
        
    } else {
        updatedRow.css('background-color', '');
        updatedRow.removeClass('red');
        
    }
    });
  });

      }
      
    $(this).children().first().blur(function() {
      $(this).parent().text(newContent);
      $(this).parent().removeClass("cellEditing");
    });
    });
    
  });
</script>