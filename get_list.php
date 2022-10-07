<?php
if($_GET['action'] == "get_dbs"){
	echo "sucsses";
}
if(isset($_GET['json_name'])){
$url = '../../datasources/'.$_GET['json_name'];
$pg_json = json_decode(file_get_contents($url), true);
$host        = "host = ".$pg_json['host'];
$port        = "port = ".$pg_json['port'];
$dbname      = "dbname = ".$pg_json['database'];
$credentials = "user =".$pg_json['username']." password=".$pg_json['password'];
/*if(isset($_GET['action'])){
	if(!(realpath("../data_756983")!== false and is_dir("../data_756983"))){
						mkdir("../data_756983");
					}*/
//get all dtabasess
if($_GET['action'] == "get_dbs"){
	$con = pg_connect("$host $port $dbname $credentials");
   if(!$con) {
      exit("Error : Unable to open database");
   } else {
	   $dbs = array();
      $query = "SELECT datname FROM pg_database where datistemplate = false"; 
		$rs = pg_query($con, $query) or die("Cannot execute query: $query");
		while ($row = pg_fetch_row($rs)) {
			$db_name = $row[0];
			$dbs[] = $db_name;
			
		
		}
		echo json_encode($dbs);
		pg_close($con); 
	  
   }
}
//get chemas from db
if($_GET['action'] == "get_shemas"){
	$dbname = "dbname = ".$_GET['database'];
	$con = pg_connect("$host $port $dbname $credentials");
   if(!$con) {
      exit("Error : Unable to open database");
   } else {
	   $chms = array();
      $query = "SELECT schema_name FROM information_schema.schemata where schema_owner != 'postgres'"; 
		$rs = pg_query($con, $query) or die("Cannot execute query: $query");
		while ($row = pg_fetch_row($rs)) {
			$chms_name = $row[0];
			$chms[] = $chms_name;
		}
		echo json_encode($chms);
		pg_close($con); 
	  
   }
}
//get tabels from chema
if($_GET['action'] == "get_tabels"){
	$dbname = "dbname = ".$_GET['database'];
	$chema_name = $_GET['chema'];
	$con = pg_connect("$host $port $dbname $credentials");
   if(!$con) {
      exit("Error : Unable to open database");
   } else {
	   $tbl = array();
      $query = "SELECT table_name FROM information_schema.tables where table_schema='".$chema_name."'"; 
		$rs = pg_query($con, $query) or die("Cannot execute query: $query");
		while ($row = pg_fetch_row($rs)) {
			$table_name = $row[0];
			$tbl[] = $table_name;
		}
		echo json_encode($tbl);
		pg_close($con); 
	  
   }
}
//get clums of tabel

if($_GET['action'] == "get_table"){
	$dbname = "dbname = ".$_GET['database'];
	$chema_name = $_GET['chema'];
	$table_name = $_GET['table'];
	$con = pg_connect("$host $port $dbname $credentials");
   if(!$con) {
      exit("Error : Unable to open database");
   } else {
	   $fp = fopen("../".$table_name.'.csv', 'w');
	   $clm = array();
      $query = "SELECT column_name FROM information_schema.columns WHERE table_schema = '".$chema_name."' AND table_name = '".$table_name."' order by ordinal_position asc"; 
		$rs = pg_query($con, $query) or die("Cannot execute query: $query");
		while ($row = pg_fetch_row($rs)) {
			$clm_name = $row[0];
			$title_data[]=$row[0];
		}
		fputcsv($fp, array_values($title_data),",");
		$query3 = "select * from ".$chema_name.".".$table_name; 
							$rs3 = pg_query($con, $query3) or die("Cannot execute query: $query3 <br>");
		
							while ($row3 = pg_fetch_row($rs3)) {
										fputcsv($fp, array_values($row3),",");
									
							}
							fclose($fp);
							echo "sucsses";
		pg_close($con); 
	  
   }
}
}
?>
