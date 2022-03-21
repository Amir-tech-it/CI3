<html>
 
<body>
<a href="http://localhost/CI3/products/view">View</a><br><br>
<h2>HTML Forms</h2>

<form method="post" action="<?php echo base_url('products/create');?>" enctype="multipart/form-data" id="sbmt-frm" >
<div class="successmsg"></div>
 
  <label for="name">Name:</label><br>
  <input type="text" id="name" value="" name="name" ><br>

  <div class="name_error all_errors"></div>
<br>
  <label for="email">Email:</label><br>
  <input type="text" id="email" value="" name="email"><br>
<div class="email_error all_errors"></div>
<br> 
	  
	<input type="file" name="userfile"  />
<div class="userfile_error all_errors"></div>
 

  <input type="submit" value="Submit">
</form> 
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript">
  
  $(document).ready(function () {

  $(document).on('submit', '#sbmt-frm', function (e) {
    e.preventDefault();
    var formObj = $(this);
    $('.all_errors').empty();
    // $('.direct_access_error').hide();
    $.ajax({
      url: "<?php echo base_url("products/create");?>",
      data: new FormData(this),
      type: "POST",
      dataType: "JSON",
       contentType: false,
      processData: false,
      success: function (data) {
         console.log(data);
        if (data.response == true) {
           $('.successmsg').html(data.success);
           // location.href = base_url + data.redirect_url;
        } else {
          if(data.image_errors){
            $('.userfile_error').html(data.image_errors);
          }
          if(data.form_errors){
            errors(data.form_errors);
          }
        }
        // else if (data.response == false){
        //             $('.name_error').html(data.form_errors.name);
        //             $('.email_error').html(data.form_errors.email);     
        //   $('.image_error').html(data.image_errors);
        // }
      }
    });
  });


  function errors(errors = '') {
    $.each(errors, function (key, value) {
      $('.' + key + '_error').html(value);
    });
    return false;
  }

});
</script>

</body>
</html>