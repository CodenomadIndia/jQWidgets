<?php
$dbHR = mysqli_connect( "localhost", "odbc", "odbc", "hr" );
if( !$dbHR ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );
mysqli_select_db( $dbHR, "hr" ); // Select this DB in order to use "hr" as the link string in the sql statements

$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );
mysqli_select_db( $dbTCS, "tcs" ); // Select this DB in order to use "tcs" as the link string in the sql statements

$query  = "SELECT ";
$query .= "   hr.employee.EmplID, ";
$query .= "   hr.employee.DisplayName ";
$query .= "FROM ";
$query .= "   hr.employee ";
$query .= "WHERE ";
$query .= "   ( hr.employee.EmplID NOT IN ( SELECT tcs.employee.EmplID FROM tcs.employee WHERE tcs.employee.Enabled ) ) ";
$query .= "AND ";
$query .= "   hr.employee.Enabled ";
$query .= "ORDER BY ";
$query .= "   hr.employee.DisplayName";

$result = $dbTCS->prepare( $query );

if( !$result->execute() ){
   trigger_error( $result->error, E_USER_ERROR );
}else{
   $result->bind_result(
      $EmplID,
      $DisplayName
   );

   while( $result->fetch() ){
      $dataArray[] = array(
         "EmplID"      => $EmplID,
         "DisplayName" => $DisplayName
      );
   };
   echo json_encode( $dataArray );
}

$result->close();
$dbHR->close();
$dbTCS->close();
?>
