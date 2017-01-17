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
        
</head>
<body>

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
          <li><a href="<?php echo $BASE; ?>">Home</a></li>
          <?php if ($page=='dashboard'): ?>
            
              <li><a href="<?php echo $BASE; ?>logout">Abmelden</a></li>
            
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <?php echo $this->render($content,$this->mime,get_defined_vars()); ?>  
    </div>
  </div>

  <script src="<?php echo $BASE; ?>/ui/js/bootstrap.js"></script>
  <script src="<?php echo $BASE; ?>/ui/js/jquery.js"></script>

</body>
</html>
