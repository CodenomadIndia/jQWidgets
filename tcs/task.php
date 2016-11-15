<?php
$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );

if( isset( $_GET[ "update" ] ) ){
   $newTask = $_GET[ "NewTask" ];
   $oldTask = $_GET[ "OldTask" ];

   $query  = "UPDATE task SET Task = '$newTask' WHERE Task = '$oldTask'";

   $result = $dbTCS->prepare( $query );
   if( $result->execute() ){
      $query = "UPDATE tcs SET Task = '$newTask' WHERE Task = '$oldTask'"; // Update the main tcs table's matching Task field records
      mysqli_query( $dbTCS, $query );
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else if( isset( $_GET[ "insert" ] ) ){
   $task = $_GET[ "Task" ];

   $query  = "INSERT INTO task ( Task ) VALUES ( '$task' )";

   $result = $dbTCS->prepare( $query );
   if( $result->execute() ){
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else if( isset( $_GET[ "delete" ] ) ){
   $task = $_GET[ "Task" ];

   $query  = "DELETE FROM task WHERE Task = '$task' ";

   $result = $dbTCS->prepare( $query );
   if( $result->execute() ){
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else{ // select
   $query  = "SELECT Task FROM task WHERE Enabled ORDER BY Task";

   $result = $dbTCS->prepare( $query );
   $result->execute();
   $result->bind_result( // i = integer, d = double, s = string, b = blob
      $task
   );

   while( $result->fetch() ){
      $dataArray[] = array(
         "Task" => $task
      );
   };

   echo json_encode( $dataArray );
};

$result->close();
$dbTCS->close();
?>
 