<?php
$dbHR = mysqli_connect( "localhost", "odbc", "odbc", "hr" );
if( !$dbHR ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );
mysqli_select_db( $dbHR, "hr" ); // Select this DB in order to use "hr" as the link string in the sql statements

$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );
mysqli_select_db( $dbTCS, "tcs" ); // Select this DB in order to use "tcs" as the link string in the sql statements

$query  = "SELECT ";
$query .= "   tcs.employee.EmplID AS EmployeeID, "; //Needs to be different than the EmplID field name in the grid
$query .= "   hr.employee.DisplayName ";
$query .= "FROM ";
$query .= "   tcs.employee, ";
$query .= "   hr.employee ";
$query .= "WHERE ";
$query .= "   tcs.employee.EmplID = hr.employee.EmplID ";
$query .= "   AND ";
$query .= "   tcs.employee.Active ";
$query .= "   AND ";
$query .= "   tcs.employee.Enabled ";
$query .= "ORDER BY ";
$query .= "   hr.employee.DisplayName";

$result = $dbTCS->prepare( $query );

if( !$result->execute() ){
   trigger_error( $result->error, E_USER_ERROR );
}else{
   $result->bind_result(
      $employeeID,
      $displayName
   );

   while( $result->fetch() ){
      $dataArray[] = array(
         "EmployeeID"  => $employeeID,
         "DisplayName" => $displayName
      );
   }
   echo json_encode( $dataArray );
}

$result->close();
$dbHR->close();
$dbTCS->close();
?>
