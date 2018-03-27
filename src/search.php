<?php
session_start();
if(!isset($_SESSION['username'])) {
        header('Location:index.php');
	}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="icon" href="nitc.png">

<style>
#records {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}
#records td, #records th {
    border: 1px solid #ddd;
    padding: 8px;
}
#records tr:nth-child(even){background-color: #f2f2f2;}
#records tr:hover {background-color: #ddd;}
#records th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}    
    
body{
    background-color: #f2f2f2;
}
input[type=submit] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
button{
    background-color: #4CAF90;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
input[type=text], select, textarea{
    width: 90%; /* Full width */
    padding: 12px; /* Some padding */ 
    border: 1px solid #ccc; /* Gray border */
    border-radius: 4px; /* Rounded borders */
    box-sizing: border-box; /* Make sure that padding and width stays in place */
    margin-top: 6px; /* Add a top margin */
    margin-bottom: 16px; /* Bottom margin */
    resize: vertical /* Allow the user to vertically resize the textarea (not horizontally) */
}
</style>
</head>


<body>
    <a href="adminhome.php"><img src="back.png" height="50px" width="50px"></a>
<center>
<h1>NATIONAL INSTITUTE OF TECHNOLOGY CALICUT</h1>
<h2>phD Student Project Management System</h2></center>
<br><br>

<center><button onclick="window.location.href='add.php'">Add New Project</button></center>
<br><br>

<?php
$roll="";
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$servername = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);
// Create connection
$conn = new mysqli($servername, $username, $password, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$search=mysqli_real_escape_string($conn, $_POST['search']);
$sql = "SELECT name,project.rollno,email,department,guide,guidemail,topic,status,date
FROM project,mtechstudent 
where (project.rollno=mtechstudent.rollno and project.rollno LIKE '%$search%') OR (project.rollno=mtechstudent.rollno AND mtechstudent.name LIKE '%$search%') ";
	
$result= mysqli_query($conn, $sql);
$availability=0;
if (mysqli_num_rows($result) > 0) {
    
    echo  "<table id = 'records'>"; 
  echo "<tr>";
      echo  "<th>Name</th>";
      echo  "<th>Roll No</th>";
      echo  "<th>Project</th>";
      echo  "<th>Guided By</th>";
      echo  "<th>Present Status</th>";
      echo  "<th>Modified Date</th>";
      echo  "<th>Edit</th>";
      echo  "<th>History</th>";
  echo "</tr>";
    
    while($row = mysqli_fetch_assoc($result)) {
    $availability=1;
              $roll=$row['rollno'];
  ?>
          <?php
        
        echo "<tr><td>", $row['name'] , "</td><td>" , $row['rollno'] , "</td><td>" , $row['topic'] , "</td><td>" , $row['guide'] , "</td><td>", $row['status'] , "</td><td>" , $row['date'], "</td><td><form action = 'edit.php' method = 'post'><input type = 'hidden' name = 'rollno' value = ", $roll, "><input type = 'submit' value = 'Edit'></form>", "</td><td>", "<form action = 'history.php' method = 'post'>", "<input type = 'hidden' name = 'rollno' value = '", $roll, "'>", "<input type = 'hidden' name = 'name' value = '", $row['name'], "'>", "<input type = 'submit' value = 'History'></form>", "</td></tr>" ;?>

<?php
}
}

if($availability==0)
{
  ?>
 <center>
  <?php
  echo "<br><br><br>";
   echo "No projects are available";
}
                  
mysqli_close($conn);
?> 
    </center>
    <footer style="position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    background-color: #333;
    color: white;
    text-align: center;"
  <center>
  
  Logged in as :
    <?php
     echo $_SESSION['username'];
     ?>
  
  </center>
</footer>
   
</body>
</html>
