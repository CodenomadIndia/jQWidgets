<?php

$dbTCS = mysqli_connect( "localhost", "odbc", "odbc", "tcs" );;
if( !$dbTCS ) die( "Connection failed: " . mysqli_connect_errno() . ': ' . mysqli_connect_error() );

if( isset( $_GET[ "update" ] ) ){

   // Special handling of NULL for AlphaClock and OmegaClock
   $alphaClock = $_GET[ "AlphaClock" ];
   if( $alphaClock === "null" ) $alphaClock = null;
   $omegaClock = $_GET[ "OmegaClock" ];
   if( $omegaClock === "null" ) $omegaClock = null;

   // Handle Runtime calc
   $displayTime = "";
   if( $alphaClock === null || $omegaClock === null ){
      $runtime = 0;
   }else{
      /* Logic
         d   | 0h 0m 1s  | d if( $x["ss"] > 0 )
         c   | 0h 1m     | c if( $x["hh"] = 0 && $x["mm"] > 0 )
         cd  | 0h 1m 1s  | c if( $x["hh"] = 0 && $x["mm"] > 0 ) -> d if( $x["ss"] > 0 )
         a   | 1h        | a if( $x["hh"] > 0 )
         abd | 1h 0m 1s  | a if( $x["hh"] > 0 ) -> b if( $x["hh"] > 0 && $x["ss"] > 0 ) -> d if( $x["ss"] > 0 )
         ac  | 1h 1m     | a if( $x["hh"] > 0 ) -> c if( $x["hh"] = 0 && $x["mm"] > 0 )
         abd | 1h 1m 59s | a if( $x["hh"] > 0 ) -> b if( $x["hh"] > 0 && $x["ss"] > 0 ) -> d if( $x["ss"] > 0 )
      */
      $alphaTime = strtotime( $alphaClock );
      $omegaTime = strtotime( $omegaClock );
      $runtime   = $omegaTime - $alphaTime;
      $x = Sec2Time( $runtime );
      if( $x["hh"] > 0 ) $displayTime .= $x["hh"] . "h ";
      if( $x["mm"] > 0 ) $displayTime .= $x["mm"] . "m ";
      $runtime = $runtime / 60; // Set runtime to minutes
      //echo $displayTime;
      //echo $alphaClock, "\n", $omegaClock, "\n";
      //echo $alphaTime,  "\n", $omegaTime,  "\n";
      //echo $runtime,    "\n", gettype( $runtime );
      //return;
   }

   // Update the record
   $query  = "UPDATE tcs SET ";
   $query .= "   EmplID = ?, ";
   $query .= "   DisplayName = ?, ";
   $query .= "   ProjectID = ?, ";
   $query .= "   Project = ?, ";
   $query .= "   ChargeLine = ?, ";
   $query .= "   Task = ?, ";
   if( $alphaClock === null ){
      $query .= "   AlphaClock = NULL, ";
   }else{
      $query .= "   AlphaClock = '$alphaClock', ";
   }
   if( $omegaClock === null ){
      $query .= "   OmegaClock = NULL, ";
   }else{
      $query .= "   OmegaClock = '$omegaClock', ";
   }
   $query .= "   Runtime = $runtime, ";
   $query .= "   DisplayTime = '$displayTime', ";
   $query .= "   Notation = ?, ";
   $query .= "   Closed = ?, ";
   $query .= "   Enabled = ? ";
   $query .= "WHERE RecordID = ? ";

   $result = $dbTCS->prepare( $query );
   $result->bind_param( // i = integer, d = double, s = string, b = blob
      "sssssssiii",
      $_GET[ "EmplID"      ],
      $_GET[ "DisplayName" ],
      $_GET[ "ProjectID"   ],
      $_GET[ "Project"     ],
      $_GET[ "ChargeLine"  ],
      $_GET[ "Task"        ],
      $_GET[ "Notation"    ],
      $_GET[ "Closed"      ],
      $_GET[ "Enabled"     ],
      $_GET[ "RecordID"    ]
   );

   if( $result->execute() ){
      //// Update the Project field of all the records for the same Work Order
      //$projectID = $_GET[ "ProjectID" ];
      //$project = $_GET[ "Project" ];
      //$query = "UPDATE tcs SET Project = '$project' WHERE ProjectID = '$projectID'";
      //mysqli_query( $dbTCS, $query );
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else if( isset( $_GET[ "insert" ] ) ){

   // Find or create job tag
   $projectID = $_GET[ "ProjectID" ];
   $query = "SELECT JobTag FROM tcs WHERE ProjectID = '$projectID'";
   $result = mysqli_query( $dbTCS, $query );
   $found = $result->num_rows;

   if( $found ){
      $row = mysqli_fetch_assoc( $result );
      $jobTag = $row[ "JobTag" ];
   }else{
      mysqli_free_result( $result );
      $query = "SELECT MAX( JobTag ) AS JobTag FROM tcs";
      $result = mysqli_query( $dbTCS, $query );
      $row = mysqli_fetch_assoc( $result );
      $jobTag = $row[ "JobTag" ];
      $jobTag++;
   }
   mysqli_free_result( $result );
   // echo "jobTag: " . $jobTag;
   // return;

   // Insert values
   $project = $_GET[ "Project" ];
   $chargeLine = $_GET[ "ChargeLine" ];
   $query  = "INSERT INTO tcs ( JobTag, ProjectID, Project, ChargeLine ) VALUES ( '$jobTag', '$projectID', '$project' , '$chargeLine' )";
   $result = $dbTCS->prepare( $query );
   if( $result->execute() ){ //
      // Update the Project field of all the records for the same ProjectID
      //$project = $_GET[ "Project" ];
      //$query = "UPDATE tcs SET Project = '$project' WHERE ProjectID = '$projectID'";
      //mysqli_query( $dbTCS, $query );
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else if( isset( $_GET[ "delete" ] ) ){
   $query  = "DELETE FROM tcs WHERE ProjectID = ?";
   $result = $dbTCS->prepare( $query );
   $result->bind_param( "s", $_GET[ "ProjectID" ] );

   if( $result->execute() ){
      echo json_encode( array( "result" => "true" ) ); // '{"result":"true"}'
   }else{
      trigger_error( $result->error, E_USER_ERROR );
   }

}else{ // SELECT

//   $whereClause = "";
//   $loginID = $_GET[ "LoginID" ];
//   if( $loginID > "" ){
//      $whereClause = " WHERE EmplID LIKE '%$loginID%' "; // Records can have multiple employees
//   }else{
//      $whereClause = " ";
//   }

//   if( strtolower( $_GET[ "HackAway" ] ) == "false" ){
//      $whereClause .= " AND Enabled ";
//   }else{
//      $whereClause .= " ";
//   }

//echo $whereClause;return;

   $query  = "SELECT ";
   $query .= "   JobTag, ";
   $query .= "   EmplID, ";
   $query .= "   DisplayName, ";
   $query .= "   ProjectID, ";
   $query .= "   Project, ";
   $query .= "   ChargeLine, ";
   $query .= "   Task, ";
   $query .= "   AlphaClock, ";
   $query .= "   OmegaClock, ";
   $query .= "   Runtime, ";
   $query .= "   DisplayTime, ";
   $query .= "   Notation, ";
   $query .= "   Closed, ";
   $query .= "   Enabled, ";
   $query .= "   Modified, ";
   $query .= "   Created, ";
   $query .= "   RecordID ";
   $query .= "FROM ";
   $query .= "   tcs ";
// $query .= $whereClause ;
   $query .= "WHERE " ;
   $query .= "   Enabled ";
   $query .= "ORDER BY ";
   $query .= "   JobTag DESC, ";
   $query .= "   RecordID DESC ";

//echo $query;return;

   $result = $dbTCS->prepare( $query );
   $result->execute();
   $result->bind_result(
      $jobTag,
      $emplID,
      $displayName,
      $projectID,
      $project,
      $chargeLine,
      $task,
      $alphaClock,
      $omegaClock,
      $runtime,
      $displayTime,
      $notation,
      $closed,
      $enabled,
      $modified,
      $created,
      $recordID
   );

   while( $result->fetch() ){
      $dataArray[] = array(
         "JobTag"      => $jobTag,    
         "EmplID"      => $emplID,    
         "DisplayName" => $displayName,
         "ProjectID"   => $projectID,   
         "Project"     => $project,   
         "ChargeLine"  => $chargeLine,   
         "Task"        => $task,   
         "AlphaClock"  => $alphaClock,  
         "OmegaClock"  => $omegaClock,  
         "Runtime"     => $runtime,     
         "DisplayTime" => $displayTime,
         "Notation"    => $notation,    
         "Closed"      => $closed,      
         "Enabled"     => $enabled,
         "Modified"    => $modified,
         "Created"     => $created,
         "RecordID"    => $recordID     
      );
   };
   echo json_encode( $dataArray );
}

$result->close();
$dbTCS->close();

function Sec2Time( $time ){
   if( is_numeric( $time ) ){
      $value = array(
      // "wk" => 0,
      // "dd" => 0,
         "hh" => 0,
         "mm" => 0
      // "ss" => 0
      );
   // if( $time >= 604800 ){
   //    $value["wk"] = floor( $time / 604800 );
   //    $time = ( $time % 604800 );
   // }
   // if( $time >= 86400 ){
   //    $value["dd"] = floor( $time / 86400 );
   //    $time = ( $time % 86400 );
   // }
      if( $time >= 3600 ){
         $value["hh"] = floor( $time / 3600 );
         $time = ( $time % 3600 );
      }
      if( $time >= 60 ){
         $value["mm"] = floor( $time / 60 );
         $time = ( $time % 60 );
      }
   // $value["ss"] = floor( $time );
      return (array) $value;
   }else{
      return (bool) FALSE;
   }
}
?>
 