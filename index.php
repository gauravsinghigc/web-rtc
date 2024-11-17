<?php
require "config.php";
$SendFromUserId = 1;
$SendRequestForUserId = 2;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Video Call App</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <section class="container p-3">
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="w-75">
          <?php if (isset($_GET['failed'])) { ?>
            <p class="text-center bg-warning p-2"><b>Alert:</b> <?php echo $_GET['failed']; ?>
              <a href="index.php" class="pull-right">Remove</a>
            </p>
          <?php } ?>
        </div>
      </div>
      <div class="col-md-12">
        <div class="video-call d-flex mx-auto">
          <div class="shadow-sm p-2">
            <video class="video1" id='user-1' autoplay playsinline></video>
            <video class="video2" id='user-2' autoplay playsinline></video>
          </div>
        </div>
        <hr>
        <form class="d-flex mx-auto" action="handler/response.php" method="POST">
          <input type="text" name="call_to" value='<?php echo $SendRequestForUserId; ?>' hidden>
          <button type="submit" value='<?php echo $SendFromUserId; ?>' name="StartCall" class="btn btn-sm btn-success">Start Video Call</button>
        </form>
      </div>
    </div>
  </section>
</body>
<script src="handle.js"></script>

</html>