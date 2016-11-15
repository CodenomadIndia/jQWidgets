<?php
$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );

$query  = "SELECT ";
$query .= "   ProjectID AS ProjID, "; //Needs to be different than the ProjectID field name in the grid
$query .= "   Project ";
$query .= "FROM ";
$query .= "   project ";
$query .= "WHERE ";
$query .= "   Active ";
$query .= "   AND ";
$query .= "   NOT Closed ";
$query .= "   AND ";
$query .= "   Enabled ";
$query .= "ORDER BY ";
$query .= "   Project";

$result = $dbTCS->prepare( $query );
if( !$result->execute() ){
   trigger_error( $result->error, E_USER_ERROR );
}else{
   $result->bind_result(
      $projID,
      $project
   );

   while( $result->fetch() ){
      $dataArray[] = array(
         "ProjID"  => $projID,
         "Project" => $project
      );
   }
   echo json_encode( $dataArray );
}

$result->close();
$dbTCS->close();
?>
