<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>
<body>
  <a href="http://localhost/CI3/products/hopage">Home</a><br><br>
<table>
  <tr>
    <th>Company</th>
    <th>Contact</th>
    <th>Edit</th>
  </tr>
 <?php
  foreach($data as $row)
  {?>
  <tr>
  <td><?php echo $row->name;?></td>
  <td><?php echo $row->email;?></td>
  <td><a href="http://localhost/CI3/products/edit?edit=<?php echo $row->id; ?>">Edit</a>&nbsp<a href="http://localhost/CI3/products/delete?del=<?php echo $row->id; ?>">Delete</a></td>
 </tr>
 <?php
  }
   ?>
</table>
</body>
</html>