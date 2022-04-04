<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<base href="<?php echo base_url(); ?>">
<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>'
</script>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

.form-inline {  
  display: flex;
  flex-flow: row wrap;
  align-items: center;
}
  
.form-inline label {
  margin: 5px 10px 5px 0;
}

.form-inline input {
  vertical-align: middle;
  margin: 5px 10px 5px 0;
  padding: 10px;
  background-color: #fff;
  border: 1px solid #ddd;
}

.form-inline button {
  padding: 10px 20px;
  background-color: dodgerblue;
  border: 1px solid #ddd;
  color: white;
  cursor: pointer;
}

.form-inline button:hover {
  background-color: royalblue;
}

@media (max-width: 800px) {
  .form-inline input {
    margin: 10px 0;
  }
  
  .form-inline {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>
</head>
<body>


<a href="http://localhost/CI3/products/login_view">login</a><br><br>
<a href="http://localhost/CI3/products/hopage">View</a><br><br>
<a href="http://localhost/CI3/products">register</a><br><br>

<form class="form-inline" id="login-frm">


  <div class="successmsg"></div>
  <label for="email">Email:</label>
  <input type="email" id="email" placeholder="Enter email" name="email">
  <div class="email_error all_errors"></div>
<br>
  <label for="pwd">Password:</label>
  <input type="password" id="psw" placeholder="Enter password" name="psw">
  <div class="psw_error all_errors"></div>
  <label>

    <input type="checkbox" name="remember"> Remember me
  </label>
  <input type="submit" value="Submit">
</form>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript">
  
  $(document).ready(function () {

  $(document).on('submit', '#login-frm', function (e) {
    e.preventDefault();
    var formObj = $(this);
    // $('.all_errors').empty();
    // $('.direct_access_error').hide();
    $.ajax({
      url: "<?php echo base_url("products/login");?>",
      data: new FormData(this),
      type: "POST",
      dataType: "JSON",
       contentType: false,
      processData: false,
      success: function (data) {
         console.log(data);
        if (data.response == true) {
           // $('.successmsg').html(data.success);
            location.href = base_url + data.redirect_url;
           

        } 
        
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
