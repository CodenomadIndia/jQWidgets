<?php 
/* $query=mysql_connect("localhost","root","");
mysql_select_db("tcs",$query);  */
session_start();
$sess_id = $_SESSION['id'];
//echo $sess_id;
include('connect.php'); 
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM session WHERE session_id='$sess_id'"); 
    $stmt->execute();
	$result = $stmt->fetchAll();
	
	
    // set the resulting array to associative
    //$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
	
    
}

catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
/* $select = "SELECT * FROM session WHERE session_id='$sess_id'";
$result2=mysql_query($select);
$num = mysql_num_rows($result2); */

if(count($result)==0)
{
	try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt1 = $conn->prepare("SELECT * FROM tcs"); 
    $stmt1->execute();
	$result1 = $stmt1->fetchAll();
	
	
    // set the resulting array to associative
    //$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
	
    
}

catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
	
	/* $query5='SELECT * FROM tcs';
 $result6=mysql_query($query5); */
 foreach( $result1 as $row6 ) {
  ?>
  <li  draggable="true" ondragstart="myfunc(event,'<?php echo $row6['Project'].','.'project'; ?>');" ><?php echo $row6['Project']; ?></li>
<?php
}
}
else
{	

foreach( $result as $row2 ) {
	$name = $row2['name'];	
	
}
 $stmt2 = $conn->prepare("SELECT * FROM tcs WHERE DisplayName='$name'"); 
    $stmt2->execute();
	$result2 = $stmt2->fetchAll();

/* $select1 = "SELECT * FROM tcs WHERE DisplayName='$name'";
$result3 = mysql_query($select1); */
foreach( $result2 as $result4 ) {
$project[] = $result4['Project'];
}
$array = array_unique($project);

$newarray = array_values($array);
for($i=0;$i<count($newarray);$i++)
{ ?>
	<li draggable="true" ondragstart="myfunc(event,'<?php echo $newarray[$i].','.'project'; ?>');" ><?php echo $newarray[$i]?></li>
<?php }
}
 ?>





