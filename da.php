<?php

//Database connectivity
$servername = "localhost";
$user = "root";
$password = "";
$dbname = "projects";
$results_per_page = 5;

$conn = mysqli_connect($servername, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; }; 
$start_from = ($page-1) * $results_per_page;

$selectSQL = "SELECT * FROM `users` GROUP BY `timestamp` LIMIT ".$start_from." , ".$results_per_page;
//$selectSQL = "SELECT * FROM `users` GROUP BY `timestamp`";
$username="JatinChauhanJoy";

  if( !( $selectRes = mysqli_query( $conn , $selectSQL ) ) ){
    echo 'Retrieval of data from Database Failed - #';
  }else{
    ?>
    <table class='table table-hover table-responsive table-inverse'>
            <thead class="thead-inverse">
            	<td class="col-md-2"><strong>Username</strong></td>
            	<td class="col-md-6"><strong>URL</strong></td>
                <td class="col-md-3"><strong>Timestamp</strong></td>
            </thead>
         <?php
              if( mysqli_num_rows( $selectRes )==0 ){
                echo '<tr><td colspan="8">No Tweets Found !</td></tr>';
              }else{
                while( $row = mysqli_fetch_assoc( $selectRes ) ){
				  if($row['user'] == $username){

                  $ur= "<a href=\"".$row['url']."\" target=\"_newtab\">".$row['url']."</a>";
                  echo "
            <tr>
                <td>{$row['username']}</td>
                <td>{$ur}</td>
                <td>{$row['timestamp']}</td>
            </tr>\n";
              }
            }
        }
            $sql = "SELECT COUNT(DISTINCT `tweetid`) AS total FROM `users` WHERE `users`.`user` = '$username' "; 
            $result = $conn->query($sql);

            $row = $result->fetch_assoc();
            $total_pages = ceil($row["total"] / $results_per_page);
            
            for ($i=1; $i<=$total_pages; $i++) {  // print links for all pages
                        //echo "<a href='dash.php?oauth_token=".$_SESSION['oauth_token']."&oauth_verifier=".$oauth_verifier."&page=".$i."'";
                        echo "<a href='da.php?page=".$i."'";
                        if ($i==$page)  echo " class='curPage'";
                        echo ">".$i."</a> "; 
            };
        ?> 
       
        </table>
<script src="jquery-3.2.1.min.js"></script>
<script src="bootstrap-3.3.6/js/bootstrap.min.js"></script>
<?php
  }

?>