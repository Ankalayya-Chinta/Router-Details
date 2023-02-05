<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>  
   
   <div class="row">
    <div class="col-lg-12">
        <h1>Upload Router Details</h1>
                   
    </div>
</div>


<form action="<?php echo base_url();?>/index.php/importrouterdata/import" method="post" enctype="multipart/form-data" id="userfile" name="userfile">
  <div>
    <input type="file" accept=".xls, .xlsx" id="userfile" name="userfile" class="mdl-button mdl-js-button mdl-button--raised">
  </div>
  <br/>
  <div>
    <input type="submit" value="Upload" class="mdl-button mdl-js-button mdl-button--raised mdl-button--accent">
  </div>
</form>
