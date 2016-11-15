<?php
$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );
mysqli_select_db( $dbTCS, "tcs" ); // Select this DB in order to use "tcs" as the link string in the sql statements

if( isset( $_GET[ "update" ] ) ){
   $query  = "UPDATE ";
   $query .= "   tcs.employee ";
   $query .= "SET ";
   $query .= "   tcs.employee.Operation = ?, ";
   $query .= "   tcs.employee.Setup = ?, ";
   $query .= "   tcs.employee.Maintenance = ?, ";
   $query .= "   tcs.employee.Active = ? ";
   $query .= "WHERE ";
   $query .= "   tcs.employee.EmplID = ? ";

   $result = $dbTCS->prepare( $query );
   $result->bind_param(
      "siiis",
      $_GET[ "Operation"   ],
      $_GET[ "Setup"       ],
      $_GET[ "Maintenance" ],
      $_GET[ "Active"      ],
      $_GET[ "EmplID"      ]
   );

   if( $result->execute() ){
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

   //echo "query = " .$query;
   //echo "result = " .$result;
}else if( isset( $_GET[ "insert" ] ) ){
   $query  = "INSERT INTO tcs.employee ( tcs.employee.EmplID, tcs.employee.DisplayName ) VALUES ( ?, ? )";
   $result = $dbTCS->prepare( $query );
   $result->bind_param(
      "ss",
      $_GET[ "EmplID" ],
      $_GET[ "DisplayName" ]
   );

   if( $result->execute() ){
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else if( isset( $_GET[ "delete" ] ) ){
   $query  = "DELETE FROM tcs.employee WHERE tcs.employee.EmplID = ? ";
   $result = $dbTCS->prepare( $query );
   $result->bind_param( "s", $_GET[ "EmplID" ] );

   if( $result->execute() ){
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else{ // select

   $fields = "
      tcs.employee.EmplID,
      tcs.employee.DisplayName,
      tcs.employee.Active
   ";

   $query  = "SELECT " . $fields . " FROM tcs.employee WHERE tcs.employee.Enabled ORDER BY tcs.employee.DisplayName";
   $result = $dbTCS->prepare( $query );
   $result->execute();

   // i = integer, d = double, s = string, b = blob
   $result->bind_result(
      $emplID,
      $displayName,
      $active
   );

   while( $result->fetch() ){
      $dataArray[] = array(
         "EmplID"      => $emplID,
         "DisplayName" => $displayName,
         "Active"      => $active      
      );
   };

   echo json_encode( $dataArray );
};

$result->close();
$dbTCS->close();
?>
 