<?php
$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );

if( isset( $_GET[ "update" ] ) ){

   // Change existing ChargeLine in tcs table to match ChargeLine updated/changed here.
   $recordID = $_GET[ "RecordID" ];
   $query = "SELECT ChargeLine FROM charge_line WHERE RecordID = $recordID";
   $result = mysqli_query( $dbTCS, $query );
   $row = mysqli_fetch_assoc( $result );
   $oldChargeLine = $row[ "ChargeLine" ];
   mysqli_free_result( $result );

   $query  = "UPDATE ";
   $query .= "   charge_line ";
   $query .= "SET ";
   $query .= "   ChargeLine = ?, ";
   $query .= "   ProjectID = ?, ";
   $query .= "   Project = ?, ";
   $query .= "   Common = ?, ";
   $query .= "   Active = ? ";
   $query .= "WHERE ";
   $query .= "   RecordID = $recordID ";

   $result = $dbTCS->prepare( $query );
   $result->bind_param(
      "sssii",
      $_GET[ "ChargeLine" ],
      $_GET[ "ProjectID" ],
      $_GET[ "Project" ],
      $_GET[ "Common" ],
      $_GET[ "Active" ]
   );

   if( $result->execute() ){
      // Change existing ChargeLine in tcs table to match ChargeLine updated/changed here.
      $chargeLine = $_GET[ "ChargeLine" ];
      $query = "UPDATE tcs SET ChargeLine = '$chargeLine' WHERE ChargeLine = '$oldChargeLine'";
      mysqli_query( $dbTCS, $query );
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else if( isset( $_GET[ "insert" ] ) ){
   $query  = "INSERT INTO charge_line ( charge_line.ChargeLine ) VALUES ( '" . $_GET[ "ChargeLine" ] . "' )";
   $result = $dbTCS->prepare( $query );

   if( $result->execute() ){
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else if( isset( $_GET[ "delete" ] ) ){
   $query  = "DELETE FROM charge_line WHERE charge_line.ChargeLine = " . $_GET[ "ChargeLine" ];
   $result = $dbTCS->prepare( $query );

   if( $result->execute() ){
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else{ // select
   $query  = "SELECT ";
   $query .= "   ChargeLine, ";
   $query .= "   ProjectID, ";
   $query .= "   Project, ";
   $query .= "   Common, ";
   $query .= "   Active, ";
   $query .= "   RecordID "; // Need this for unique in case ChargeLine is modified
   $query .= "FROM ";
   $query .= "  charge_line ";
   $query .= "WHERE ";
   $query .= "   Enabled ";
   $query .= "ORDER BY ";
   $query .= "   ChargeLine ";
//echo $query,"\n";
//return;
   $result = $dbTCS->prepare( $query );
   $result->execute();

   // i = integer, d = double, s = string, b = blob
   $result->bind_result(
      $chargeLine,
      $projectID,
      $project,
      $common,
      $active,
      $recordID
   );

   while( $result->fetch() ){
      $dataArray[] = array(
         "ChargeLine" => $chargeLine,
         "ProjectID"  => $projectID, //  str_replace( ", ", "<br>", $projectID ), // Want to display it with breaks rather than commas
         "Project"    => $project, // str_replace( ", ", "<br>", $project ), // Want to display it with breaks rather than commas
         "Common"     => $common,
         "Active"     => $active,
         "RecordID"   => $recordID
      );
   };

   echo json_encode( $dataArray );
};

$result->close();
$dbTCS->close();
?>
 