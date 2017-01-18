<!DOCTYPE HTML>
<html>
<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Titel</title>

    <!-- Icon -->
    <link rel="icon" type="image/png" href=""> 

    <!-- Css -->
    <link href="<?php echo $BASE; ?>/ui/css/style.css" type="text/css" rel="stylesheet">
    <link href="<?php echo $BASE; ?>/ui/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet">
        
</head>
<body>

  <script type="text/javascript">
  setTimeout(function(){
  $(".droptext").fadeOut();
  }, 5000);
  </script>

  <script type="text/javascript"> 
  $(document).ready(function(){     
    $('[data-toggle="tooltip"]').tooltip();   
  });
  </script>

  <div class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <a href="<?php echo $BASE; ?>" class="navbar-brand">PADLOCK</a>
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
      <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?php echo $BASE; ?>"><?php echo $L['home']; ?></a></li>
          <li><a href="<?php echo $BASE; ?>settings"><?php echo $L['settings']; ?></a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?php echo $L['language']; ?> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#"><?php echo $L['german']; ?></a></li>
              <li><a href="#"><?php echo $L['english']; ?></a></li>
            </ul>
            </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-12 droptext">
        <?php foreach (($messages?:array()) as $message): ?>
          <p>Absatz <?php echo $message['0']; ?></p>
        <?php endforeach; ?>
      </div>
      <?php echo $this->render($content,$this->mime,get_defined_vars()); ?>  
    </div>
  </div>

  <script src="<?php echo $BASE; ?>/ui/js/jquery.js"></script>
  <script src="<?php echo $BASE; ?>/ui/js/bootstrap.js"></script>
  <script src="<?php echo $BASE; ?>/ui/js/dropdown.js"></script>
  <script src="<?php echo $BASE; ?>/ui/js/collapse.js"></script>
  <script src="<?php echo $BASE; ?>/ui/js/tooltip.js"></script>

</body>
</html>
