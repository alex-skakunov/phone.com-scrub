<!DOCTYPE html>

<html>
<head>
    <title><?=ucfirst(CURRENT_ACTION)?> — Scrubbing app</title>
    <!-- Bootstrap core CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style type="text/css">
    .starter-template {
      padding: 1rem 1.5rem;
      text-align: center;
    }
    label.disabled {
      color: lightgray;
    }
    
    body
    {
      background-color: #f5f5f5;
      padding-top: 5rem;
    }
    
    .edt
    {
      background:#ffffff; 
      border:3px double #aaaaaa; 
      -moz-border-left-colors:  #aaaaaa #ffffff #aaaaaa; 
      -moz-border-right-colors: #aaaaaa #ffffff #aaaaaa; 
      -moz-border-top-colors:   #aaaaaa #ffffff #aaaaaa; 
      -moz-border-bottom-colors:#aaaaaa #ffffff #aaaaaa; 
      width: 350px;
    }
    .edt_30
    {
      background:#ffffff; 
      border:3px double #aaaaaa; 
      font-family: Courier;
      -moz-border-left-colors:  #aaaaaa #ffffff #aaaaaa; 
      -moz-border-right-colors: #aaaaaa #ffffff #aaaaaa; 
      -moz-border-top-colors:   #aaaaaa #ffffff #aaaaaa; 
      -moz-border-bottom-colors:#aaaaaa #ffffff #aaaaaa; 
      width: 30px;
    }
    
    input {
      font-size: 16px
    }
    input.btn
    {
      font-weight: bold;
      padding: 5px;
    }
    
    input.auto-map
    {
      font-weight: normal;
      font-size: 70%;
    }

    td {
      text-align: center;
    }
    
  </style>
</head>

<body>
  <? if (!empty($_SESSION['authenticated'])) : ?>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">

          <?
            $itemsList = array('scrubbing', 'logout');
            if ('admin' == $_SESSION['userLevel']) {
              $itemsList = array('scrubbing', 'history', 'settings',  'logout');
            }
            foreach ($itemsList as $item) {
              echo '<li class="nav-item ', (CURRENT_ACTION == $item ? 'active' : ''), '">';
              echo '<a  class="nav-link" href="/scrub/?page=' . $item . '">' . ucfirst($item) . '</a>';
              echo '</li>'; 
            }
          ?>
        </ul>
      </div>

      <div style="float: right; color: gray;">
        <small>You are the <?=ucfirst($_SESSION['userLevel']);?> user</small>
      </div>
    </nav>
  <? endif; ?>
    <main role="main" class="container">
        <? if( !empty($error) ) : ?>
            <div class="alert alert-danger" role="alert">
              <?=$error?>
            </div>          
        <? endif; ?>

      <div class="starter-template">
        [template]
      </div>

      <div style="display: none; text-align: center;" id="loader">
        <img src="//upload.wikimedia.org/wikipedia/commons/d/de/Ajax-loader.gif" width="32" height="32" alt="loader" />
      </div>
    </main><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>