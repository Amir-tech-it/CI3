<html>
   
<body>
<a href="http://localhost/CI3/products/view">View</a><br><br>
<h2>Update form</h2>
  <?php
  foreach($data as $row)
  {?>
<form method="post" id="up-frm">
  <label for="name">Name:</label><br>
  <input type="text" id="name" value="<?php echo $row->name;?>" name="name" ><br>
  <div class="name_error all_errors"></div>
<br>
  <label for="email">Email:</label><br>
 
  <input type="text" id="email" value="<?php echo $row->email;?>" name="email"><br>
  <div class="email_error all_errors"></div>
<br> 
 <img src="<?php echo base_url().'/assets/uploads/'.$row->image; ?>" alt="<?php echo $row->image;?>" width="100" height="100">  
 <input type="file" name="userfile" value="<?php echo $row->image;?>" />
<div class="userfile_error all_errors"></div>


<input type="hidden" value="<?php echo $row->id;?>" name="id" >
  <input type="submit" value="Update">
</form> 
<?php
  }
?>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript">
  
  $(document).ready(function () {

  $(document).on('submit', '#up-frm', function (e) {
    e.preventDefault();
    var formObj = $(this);
    $('.all_errors').empty();
    // $('.direct_access_error').hide();
    $.ajax({
      url: "<?php echo base_url("products/updateajaximage");?>",
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