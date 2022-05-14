<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>CodePen - Product Card TO Code</title>
    <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><link rel="stylesheet" href="./style.css">
  <script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>'
</script>
  <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/style.css'); ?>">

</head>
<style type="text/css">
  
  input,
textarea {
  border: 1px solid #eeeeee;
  box-sizing: border-box;
  margin: 0;
  outline: none;
  padding: 10px;
}

input[type="button"] {
  -webkit-appearance: button;
  cursor: pointer;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
}

.input-group {
  clear: both;
  margin: 15px 0;
  position: relative;
}

.input-group input[type='button'] {
  background-color: #eeeeee;
  min-width: 38px;
  width: auto;
  transition: all 300ms ease;
}

.input-group .button-minus,
.input-group .button-plus {
  font-weight: bold;
  height: 38px;
  padding: 0;
  width: 38px;
  position: relative;
}

.input-group .quantity-field {
  position: relative;
  height: 38px;
  left: -6px;
  text-align: center;
  width: 62px;
  display: inline-block;
  font-size: 13px;
  margin: 0 0 5px;
  resize: vertical;
}

.button-plus {
  left: -13px;
}

input[type="number"] {
  -moz-appearance: textfield;
  -webkit-appearance: none;
}

</style>
<body>

  <a href="http://localhost/CI3/products/login_view">login</a><br><br>
<a href="http://localhost/CI3/products/hopage">View</a><br><br>
 <?php 

  foreach ($this->cart->contents() as $items): 
?>
</td> 

<a href="http://localhost/CI3/products/cart"><?php  print_r ($items['qty']);?>cart</a><br><br>
<?php
endforeach;
?>
<!-- partial:index.partial.html -->
<div class="card">
    <div class="left">
      <img src="https://www.dropbox.com/s/e928cht0h5crcn4/shoe.png?raw=1" alt="shoe">
      <i class="fa fa-long-arrow-left"></i>
      <i class="fa fa-long-arrow-right"></i>
    </div>
    <div class="right">
      <div class="product-info">
        <div class="product-name">
          <h1>Airmax</h1>
          <i class="fa fa-search"></i>
          <i class="fa fa-user"></i>
          <i class="fa fa-shopping-cart"></i>
        </div>

        <input type="hidden" value="150" class="price">
        <div class="details">
          <h3>Winter Collection</h3>
          <h2>Men Black Sneakers</h2>
          <form class="adcfm" id="adcfrm">
          <h4><span class="fa fa-dollar"></span>150</h4>
          <h4 class="dis"><span class="fa fa-dollar"></span>200</h4>
        </div>
        <ul>
          <li>SIZE</li>
          <li class="bg"><input type="radio" name="size" class="size" value="7" >7</li>
          <li class="bg"><input type="radio" name="size" class="size" value="8">8</li>
          <li class="bg"><input type="radio" name="size" class="size" value="9">9</li>
          <li class="bg"><input type="radio" name="size" class="size" value="10">10</li>
          <li class="bg"><input type="radio" name="size" class="size" value="11">11</li>
        </ul>
        <ul>
          <li>COLOR</li>
          <li class="yellow"><input type="radio" name="color" class="color" value="yellow"></li>
          <li class="black"><input type="radio" name="color" class="color" value="black"></li>
          <li class="blue"><input type="radio" name="color" class="color" value="blue"></li>
        </ul>

        <div class="input-group">
  <input type="button" value="-" class="button-minus" data-field="quantity">
  <input type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
  <input type="button" value="+" class="button-plus" data-field="quantity">
</div>
<input type="hidden" name="pro_id" value="2">
<input type="hidden" name="pro_name" value="shoes">

        <span class="foot"><i class="fa fa-shopping-bag"></i>Buy Now</span>

        <input type="submit" value="submit"> 
        <!-- <span class="foot atc" id="sbmtfrrm"><i class="fa fa-shopping-cart"></i>  Add TO Cart</span> -->
       </form>
      </div>
    </div>
  </div>
<!-- partial -->
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script type="text/javascript">
    
    $( document ).ready(function() {
   $(document).on('submit', '#adcfrm', function (e) {
      e.preventDefault();
    var formObj = $(this);
    // $('.all_errors').empty();
    // $('.direct_access_error').hide();
    $.ajax({
      url: "<?php echo base_url("products/cartdata");?>",
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
    })
});
    


  </script>
<script type="text/javascript">
  function incrementValue(e) {
  e.preventDefault();
  var fieldName = $(e.target).data('field');
  var parent = $(e.target).closest('div');
  var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

  if (!isNaN(currentVal)) {
    parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
  } else {
    parent.find('input[name=' + fieldName + ']').val(0);
  }
}

function decrementValue(e) {
  e.preventDefault();
  var fieldName = $(e.target).data('field');
  var parent = $(e.target).closest('div');
  var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

  if (!isNaN(currentVal) && currentVal > 0) {
    parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
  } else {
    parent.find('input[name=' + fieldName + ']').val(0);
  }
}

$('.input-group').on('click', '.button-plus', function(e) {
  incrementValue(e);
});

$('.input-group').on('click', '.button-minus', function(e) {
  decrementValue(e);
});

</script>

</body>
</html>
