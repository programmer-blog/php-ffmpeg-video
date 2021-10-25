<?php

   $allowedExts = array( "mp4");
   $destination = "uploads/";
   $lowResolution = $destination.'low-res/';
   $videoName = 'low-resolution-video';
   $error = $output = $retval = null;

   if(count($_FILES)) {
      $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
      if ((($_FILES["file"]["type"] == "video/mp4")) && ($_FILES["file"]["size"] < 300000) && in_array($extension, $allowedExts)) {
         if ($_FILES["file"]["error"] > 0){
            $error = $_FILES["file"]["error"];
         } else {
            $filePath = $destination.$videoName.".".$extension;
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
               
               /** Add low resolution video */
               exec("ffmpeg -i $filePath -vcodec libx265 -crf 28 ".$lowResolution.$videoName.'.'.$extension, $output, $retval);

              /** Add more low resolution video */
               exec("ffmpeg -i $filePath -vcodec libx264 -crf 24 ".$lowResolution.$videoName.'-2.'.$extension, $lowOutput, $lowRetval);
            }
         }
      } else { 
        $error = "Invalid file type. Please uplaod mp4 video of less than 1 MB.";
      }
   }

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>PHP Vidoe Upload & Conversion Task</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/navbar-fixed/">
   <!-- Bootstrap core CSS -->
   <link rel="stylesheet" href="css/bootstrap.min.css" >
   <!-- Optional theme -->
   <meta name="theme-color" content="#7952b3">
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/navbar-top-fixed.css" rel="stylesheet">
  </head>
  <body>
    
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">PHP Vidoe Upload & Conversion Task</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">Home</a>
        </li>
      </ul>
      
    </div>
  </div>
</nav>
<main class="container">
  <div class="bg-light p-5 rounded">
    <h1>PHP Vidoe Upload & Conversion Task</h1>
    <p class="lead">Select a video to Upload</p>
    <?php if($error) { ?> <p><?= $error ?></p> <?php } ?>
    <form enctype="multipart/form-data" name="form1" method="POST" action="index.php">
      <input type="file" class="form-control" id="file" name="file" /><br />
      <button class="btn btn-primary" type="submit">Upload</button>    
   </form>
  </div>
  <br />
  <div class="row">
      <div class="col-md-12">
        <select onchange="" class="form-control" id="video-name">
          <option>--SELECT--</option>
          <option value="<?= $lowResolution.$videoName.'.mp4'; ?>">Low Resolution Video</option>
          <option value="<?= $lowResolution.$videoName.'-2.mp4'; ?>">More Low Resolution Video</option>
        </select>
      </div>
  </div>
  <br />
  <div class="row">
     <div class="col-md-12">
     <p>
      <video id="video" height="340" width="470" onplaying="PlayVideoFromVid('PAUSE')"  onpause="PlayVideoFromVid('PLAY')" onended="ResetVideo()" preload="true" autobuffer="true" controls="true">
        <source src="<?= $lowResolution.$videoName.'.mp4'; ?>" type="video/mp4" id="mp4Video"></source>
      </video>
    </p>
     </div>
  </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(function() {
    $('#video-name').on('change', function() {
      let srcVal = $(this).val();
      $('#mp4Video').attr('src', srcVal);
    });
  });
</script>
</body>
</html>
