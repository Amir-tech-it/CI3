<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <base href="<?php echo base_url(); ?>">
    <title>Admin Login</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <script>
      var base_url = "<?php base_url(); ?>";
    </script>

    <style>
      .all_errors {
        color: red;
      }
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>

    <link href="./assets/css/signin.css" rel="stylesheet">

  </head>
  <body class="text-center">
    
    <form class="form-signin">
      <h1 class="h3 mb-3 font-weight-normal">Admin Login</h1>

      <?php if(!empty($direct_access_error)){ ?>
        <div class="alert alert-danger direct_access_error" role="alert">
          <?php echo $direct_access_error; ?>
        </div>
      <?php } ?>

      <div class="form-control">
        <label for="email" class="sr-only">Email address</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Email address"autofocus>
      </div>
      <div class="email_error all_errors"></div>

      <div class="form-control">
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
      </div>
      <div class="password_error all_errors"></div>

      <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
    </form>

    <script src="./assets/js/admin/login.js"></script>

  </body>
</html>
