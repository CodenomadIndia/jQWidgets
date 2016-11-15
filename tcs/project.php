<?php
$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );
mysqli_select_db( $dbTCS, "tcs" ); // Select this DB in order to use "tcs" as the link string in the sql statements

if( isset( $_GET[ "update" ] ) ){
   $projectID = $_GET[ "ProjectID" ];
   $project = $_GET[ "Project" ];

   // Set the table's record
   $query  = "UPDATE ";
   $query .= "   tcs.project ";
   $query .= "SET ";
   $query .= "   tcs.project.Project = '$project' ";
   $query .= "WHERE ";
   $query .= "   tcs.project.ProjectID = '$projectID' ";

   $result = mysqli_query( $dbTCS, $query );
   if( $result ){
      // Update the Project field of all the records for the same ProjectID
      $query = "UPDATE tcs.tcs SET tcs.tcs.Project = '$project' WHERE tcs.tcs.ProjectID = '$projectID'";
      mysqli_query( $dbTCS, $query );
      echo json_encode( array( "result" => "true" ) );
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else if( isset( $_GET[ "insert" ] ) ){
   $query = "INSERT INTO tcs.project ( tcs.project.ProjectID, tcs.project.Project ) VALUES ( ?, ? )";
   $result = $dbTCS->prepare( $query );
   $result->bind_param(
      "ss",
      $_GET[ "ProjectID" ],
      $_GET[ "Project" ]
   );

   if( $result->execute() ){
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }
   $result->close(); // Here instead of bottom because of different code in "update"

}else if( isset( $_GET[ "delete" ] ) ){
   $query  = "DELETE FROM tcs.project WHERE tcs.project.ProjectID = ?";
   $result = $dbTCS->prepare( $query );
   $result->bind_param( "s", $_GET[ "ProjectID" ] );

   if( $result->execute() ){
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }
   $result->close(); // Here instead of bottom because of different code in "update"

}else{ // select
   $fields = "
      tcs.project.ProjectID,
      tcs.project.Project
   ";

   $query  = "SELECT " . $fields . " FROM tcs.project WHERE tcs.project.Enabled ORDER BY tcs.project.Project";
   $result = $dbTCS->prepare( $query );
   $result->execute();

   $result->bind_result(
      $projectID,
      $project
   );

   while( $result->fetch() ){
      $dataArray[] = array(
         "ProjectID" => $projectID,
         "Project"   => $project
      );
   };

   echo json_encode( $dataArray );
}

$dbTCS->close();
?>
 