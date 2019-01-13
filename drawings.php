<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Something Something</title>
    <link rel="stylesheet" href="css/bootstrap.min.css"/>
    <meta name="viewport" content="initial-scale=1.0">
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <style media="screen" type="text/css">

    * {
        margin:0;
        padding:0
      }

      body {
        background-color: #DDD;
      }

      /* #mainContainer {
        width: 800px;

        margin: 50px auto 0 auto;
        padding: 10px;

        background-color: #FFF;
      } */

      .container {
        margin:10px auto 10px auto;
        min-width: 600px;

        border:1px solid #888;
        background-color: #FFF;
      }

      .row {
        padding: 10px;
      }

      #canvasContainer {
        text-align: center;
      }

      #c {
        margin:0 auto 0 auto;
          border:1px solid #000;
      }

      #colorPickerContainer {

      }

      .color {
        background-color: #FF0000;
        display: inline-block;
        width: 30px;
        height: 30px;
      }

      .color:not(:first-child) {
        margin-left: 5px;
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-light bg-light">
      <span class="navbar-brand mb-0 h1">Something Something</span>
      <form class="form-inline">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
      </form>
    </nav>
    <div class="container">
      <?php
      require('dbconfig.php');

      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $conn->prepare("SELECT drawingId, profName, uniName FROM drawprof_drawings, drawprof_unis, drawprof_profs WHERE drawprof_drawings.profId = drawprof_profs.profId AND drawprof_profs.uniId = drawprof_unis.uniId");
      $stmt->execute();

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="row">
          <p><strong><?=$row['profName']; ?></strong> from <u><?=$row['uniName']; ?></u></p>
          <br>
          <img src="drawings/<?=$row['drawingId'] ?>.png">
        </div>
        <?php
      }

      ?>
      <div id="footer">
      </div>
    </div>
  </body>
</html>
