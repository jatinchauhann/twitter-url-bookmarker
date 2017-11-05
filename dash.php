<?php
$name=$_GET["name"];
$username=$_GET["username"];
$location=$_GET["location"];
$about=$_GET["about"];
$profileimage=$_GET["profileimage"];
$followers=$_GET["followers"];
$following=$_GET["following"];

$servername = "localhost";
$user = "root";
$password = "";
$dbname = "projects";
$results_per_page = 12;

$tweet_found = false;
$conn = mysqli_connect($servername, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;

if ( isset($_POST['page_num']) ){
  $page_num = $_POST['page_num'];
  $redir = "http://localhost:666/booklocal/dash.php?name=".$name."&username=".$username."&location=".$location."&about=".$about."&profileimage=".$profileimage."&followers=".$followers."&following=".$following."&page=".$page_num."";
  header('Location: '. $redir);
}
$total_pages = "";

$search_user="";
$search_bool=false;
if (isset($_POST["search_user"])){
  $search_user=$_POST['search_user'];
  $search_bool=true;
  $results_per_page = 30;
  $selectSQL = "SELECT * FROM `users` WHERE `users`.`username` = '$search_user' GROUP BY `id` LIMIT ".$start_from." , ".$results_per_page."";
}else{
  $selectSQL = "SELECT * FROM `users` WHERE `users`.`user` = '$username' GROUP BY `id` LIMIT ".$start_from." , ".$results_per_page."";
}


  if( !( $selectRes = mysqli_query( $conn , $selectSQL ) ) ){
    echo 'Retrieval of data from Database Failed - #';
  }else{
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="jc">
    <link rel="shortcut icon" href="images/LOGO256.png">

 
    <title>Twitter | URLBOOK</title>
    <link rel="stylesheet" href="bootstrap-3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/divider.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
    .copyright { min-height:40px; background-color:#000000;}
    .copyright p { text-align:left; color:#FFF; padding:10px 0; margin-bottom:0px;}
    </style>

</head>
<body>
<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">TWITTER</a>
    </div>
    <ul class="nav navbar-nav">
    <?php
    $home_page = "http://localhost:666/booklocal/dash.php?name=".$name."&username=".$username."&location=".$location."&about=".$about."&profileimage=".$profileimage."&followers=".$followers."&following=".$following."&page=1";
    ?>
      <li class="active"><a href="<?php echo $home_page; ?>"> Home </a></li>
      <li class=""><a href="resume.pdf"> Contact Me </a></li>
    </ul>
    <?php
    $search_string = "dash.php?name=".$name."&username=".$username."&location=".$location."&about=".$about."&profileimage=".$profileimage."&followers=".$followers."&following=".$following."&page=1";
    ?>
<form class="navbar-form navbar-left" action="<?php echo $search_string;?>" method="post">
  <div class="input-group">
    <input type="text" name="search_user" class="form-control" placeholder="Search Username">
    <div class="input-group-btn">
      <button class="btn btn-default" type="submit">
        <i class="glyphicon glyphicon-search"></i>
      </button>
    </div>
  </div>
</form>
  <span class="pull-right">
    <form action="http://localhost:666/booklocal/index.html" method="get" accept-charset="utf-8">
      <button class="btn btn-warning navbar-btn" type="submit">Log Out</button>
    </form>  
  </span>
  </div>
</nav>

<div class="container">
    <div class="col-md-12">
        <div class="page-header">
            <h1>URL<span class="text-danger">BOOK</span></h1>
            <hr class="style17">
        </div>
    </div> 

    <div class="col-sm-3">
    <table class='table table-hover table-responsive table-striped'>
            <tr>
                <img src="<?php echo $profileimage; ?>" class="img-circle" alt="" style="width:40%">
            </tr>
            <tr><h2><?php echo $name; ?></h2></tr>
            <tr><strong><h4>@<?php echo $username; ?></h4></strong></tr>
            <tr><h4><?php echo $about; ?></h4></tr>
            <tr><h5><?php echo $location; ?></h5></tr>
            <tr><h5>Followers :<strong><?php echo $followers; ?></strong></h5></tr>
            <tr><h5>Following :<strong><?php echo $following; ?></strong></h5></tr>
            <br><br>
            </tr>   
        </table>
        
    </div>
    <div class="col-sm-8">

    <table class='table table-hover table-responsive table-inverse'>
            <thead class="thead-inverse">
              <td class="col-md-2"><strong>Username</strong></td>
              <td class="col-md-5"><strong>URL</strong></td>
                <td class="col-md-4"><strong>Timestamp</strong></td>
            </thead>
         <?php
              if( mysqli_num_rows( $selectRes )==0 ){
                echo '<tr><td colspan="8">No Tweets Found !</td></tr>';
              }else{
                while( $row = mysqli_fetch_assoc( $selectRes ) ){
                if($row['user'] == $username){

                  $tweet_found=true;

                  $ur= "<a href=\"".$row['url']."\" target=\"_newtab\">".$row['url']."</a>";
                  echo "
            <tr>
                <td>{$row['username']}</td>
                <td>{$ur}</td>
                <td>{$row['timestamp']}</td>
            </tr>\n";
              }
            }
        if(!$tweet_found)
              echo '<tr><td colspan="8">No Tweets Found !</td></tr>';
        }
        ?> 
        </table>
        <ul class="pager">
        <?php
        if(!isset($_POST["search_user"])){
        if($page != 1){
          $prev_page = "dash.php?name=".$name."&username=".$username."&location=".$location."&about=".$about."&profileimage=".$profileimage."&followers=".$followers."&following=".$following."&page=".($page-1)."";
        ?> 
          <li class="previous"><a href="<?php echo $prev_page ?>">Previous</a></li><?php } ?>
          <?php 
            if(isset($_POST['search_user'])){
            $sql = "SELECT COUNT(DISTINCT `tweetid`) AS total FROM `users` WHERE `users`.`user` = '$username' AND `users`.`username` = '$search_user' ";
            }else{
            $sql = "SELECT COUNT(DISTINCT `tweetid`) AS total FROM `users` WHERE `users`.`user` = '$username' ";
          }
            $result = $conn->query($sql);

            $row = $result->fetch_assoc();
            $total_pages = ceil($row["total"] / $results_per_page);
            $total_pages--;
            if($row["total"]%12 == 0)
              $total_pages--;
          echo "Page ".$page." of "."$total_pages";

          if($page != $total_pages){
          $next_page = "dash.php?name=".$name."&username=".$username."&location=".$location."&about=".$about."&profileimage=".$profileimage."&followers=".$followers."&following=".$following."&page=".($page+1)."";
          ?>
          <li class="next"><a href="<?php echo $next_page?>">Next</a></li> <?php }}?>
        </ul>
        
    </div> <!-- Container -->

</div>
 
 <div class="copyright">
  <div class="container">
    <div class="col-md-10">
      <p>2017 - This site is for Project Purpose - Aditya Mathur</p>
    </div>
    <div class="col-md-2">
      <ul class="bottom_ul">
      <?php
        if($page != 1){

        $first_page = "dash.php?name=".$name."&username=".$username."&location=".$location."&about=".$about."&profileimage=".$profileimage."&followers=".$followers."&following=".$following."&page=1";
      ?>
        <li><button class="btn btn-basic" style="margin-top:2px" ><a href="<?php echo $first_page ;?>">Goto First page</a></button></li>
      
      <?php 
    }
    if($page != $total_pages && $search_bool == false){
        $last_page = "dash.php?name=".$name."&username=".$username."&location=".$location."&about=".$about."&profileimage=".$profileimage."&followers=".$followers."&following=".$following."&page=".$total_pages."";
      ?>
        <li><button class="btn btn-basic" style="margin-top:2px" ><a href="<?php echo $last_page ;?>">Goto last page</a></button></li>
      
      <?php
      }
      ?>
      </ul>
    </div>
  </div>
</div>

<script src="jquery-3.2.1.min.js"></script>
<script src="bootstrap-3.3.6/js/bootstrap.min.js"></script>
</body>
</html>
<?php
  }

?>
