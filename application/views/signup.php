<html>
 
<body>

<a href="http://localhost/CI3/products/login_view">login</a><br><br>
<a href="http://localhost/CI3/products/hopage">View</a><br><br>
<h2>HTML Forms</h2>

<form method="post" enctype="multipart/form-data" id="sbmt-frm" >
<div class="successmsg"></div>

  <label for="email">email:</label><br>
  <input type="text" id="email" value="" name="email" ><br>

  <div class="email_error all_errors"></div>
<br>
  <label for="password">password:</label><br>
  <input type="text" id="psw" value="" name="psw"><br>
<div class="psw_error all_errors"></div>
<br> 
<label for="psw-repeat">password:</label><br>
  <input type="text" id="psw-repeat" value="" name="psw-repeat"><br>
<div class="pswrepeat_error all_errors"></div>
<br>

<select name="role" class="form-select" aria-label="Default select example">
  <option selected>Select Role</option>
  <option value="1">User</option>
  <option value="2">Subscriber</option>
 
</select>  
	<!-- <input type="file" name="userfile"  />
<div class="userfile_error all_errors"></div> -->
 

  <input type="submit" value="Submit">
</form> 
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript">
  
  $(document).ready(function () {

  $(document).on('submit', '#sbmt-frm', function (e) {

    
    e.preventDefault();

    var formObj = $(this);
    
    // $('.all_errors').empty();
    // $('.direct_access_error').hide();
    $.ajax({
      url: "<?php echo base_url("products/register");?>",
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
        //   if(data.image_errors){
        //     $('.userfile_error').html(data.image_errors);
        //   }
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