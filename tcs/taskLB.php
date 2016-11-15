<?php
$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );

$query  = "SELECT ";
$query .= "   Task ";
$query .= "FROM ";
$query .= "   task ";
$query .= "WHERE ";
$query .= "   Enabled ";
$query .= "ORDER BY ";
$query .= "   Task";

$result = $dbTCS->prepare( $query );
if( !$result->execute() ){
   trigger_error( $result->error, E_USER_ERROR );
}else{
   $result->bind_result(
      $task
   );

   while( $result->fetch() ){
      $dataArray[] = array(
         "Task" => $task
      );
   }
   echo json_encode( $dataArray );
}
$result->close();
$dbTCS->close();
?>
