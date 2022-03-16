<html>

<body>
<a href="http://localhost/CI3/products/view">View</a><br><br>
<h2>Update form</h2>
  <?php
  foreach($data as $row)
  {?>
<form method="post" action="<?php echo base_url('products/update');?>">
  <label for="name">Name:</label><br>
  <input type="text" id="name" value="<?php echo $row->name;?>" name="name" ><br>
  <?php
  if(!empty($form_errors)){
echo($form_errors['name']); }?><br>
  <label for="email">Email:</label><br>
 
  <input type="text" id="email" value="<?php echo $row->email;?>" name="email"><br>
   <?php
  if(!empty($form_errors)){
echo($form_errors['email']); }?>
<br>

<input type="hidden" value="<?php echo $row->id;?>" name="id" >
  <input type="submit" value="Update">
</form> 
<?php
  }
?>

</body>
</html>