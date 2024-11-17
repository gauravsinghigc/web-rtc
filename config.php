<?php
DEFINE("HOST", "localhost");
DEFINE("USER", "root");
DEFINE("PASSWORD", "");
DEFINE("DATABASE", "webrtc");


$DBConnection = new mysqli(HOST, USER, PASSWORD, DATABASE);
define("DBConnection", $DBConnection);

//Check function
function CHECK($SQL, $die = false)
{
  global $DBConnection;
  $Check = "$SQL";

  //die entry
  if ($die == true) {
    die($Check);
  }
  $Query = mysqli_query(DBConnection, $Check);
  $Count = mysqli_num_rows($Query);
  if ($Count == 0 or $Count == null) {
    return false;
  } else {
    return true;
  }
}

//INsert new data
function INSERT($tablename, array  $RequestedData, $die = false)
{
  $TableValues = "";
  $Datatables = "";

  $table_columns = array_keys($RequestedData);
  $arraycount = count($table_columns);
  $mainarray = $arraycount - 1;
  $countkeys = 0;

  //echo "<br><b style='color:green;'>• REQUESTING </b> -> <b>[$tablename]</b> ---- <b style='color:green;'>Sent!</b> <br><b style='color:red'><i> Data Received</i></b> <b>[$tablename]</b> @ [<br>";
  foreach ($RequestedData as $key => $data) {
    $countkeys++;
    $$data = $data;
    global $$data;
    //echo "&nbsp;&nbsp; <b style='color:grey;'> Index:</b> $countkeys ( <b> " . $key . "</b> : " . $data . " ) <br>";

    if ($countkeys <= $mainarray) {
      $TableValues .= "'" . $data . "', ";
    } else {
      $TableValues .= "'" . $data . "' ";
    }

    if ($countkeys <= $mainarray) {
      $Datatables .= "$key, ";
    } else {
      $Datatables .= "$key ";
    }
  }

  //echo "]<br> ---<b style='color:primary;'>END</b><br><hr>---";


  $InsertNewData = "INSERT INTO $tablename ($Datatables) VALUES ($TableValues)";
  //die entry
  if ($die == true) {
    die($InsertNewData);
  }
  $Query = mysqli_query(DBConnection, $InsertNewData);
  if ($Query == true) {
    return true;
  } else {
    return false;
  }
}

//Insert Data
function SAVE($tablename, array $INSERT, $die = false)
{
  global $DBConnection;
  $tablename = $tablename;
  $Datatables = "";
  $TableValues = "";
  $tablerows = $INSERT;
  $arraycount = count($tablerows);
  $mainarray = $arraycount - 1;

  foreach ($tablerows as $key => $value) {
    global $$value;
  }


  foreach ($tablerows as $key => $value) {
    if ($key == $mainarray) {
      $TableValues .= "'" . $$value . "'";
    } else {
      $TableValues .= "'" . $$value . "', ";
    }
  }

  foreach ($tablerows as $key => $value) {
    if ($key == $mainarray) {
      $Datatables .= "$value";
    } else {
      $Datatables .= "$value, ";
    }
  }
  $InsertNewData = "INSERT INTO $tablename ($Datatables) VALUES ($TableValues)";

  //die entry
  if ($die == true) {
    die($InsertNewData);
  }
  $Query = mysqli_query($DBConnection, $InsertNewData);
  if ($Query == true) {
    return true;
  } else {
    return false;
  }
}


//Select Data
function SELECT($SQL, $die = false)
{
  $SELECT = "$SQL";

  if ($die == true) {
    die($SELECT);
  }

  $QUERY = mysqli_query(DBConnection, $SELECT);
  if ($QUERY == true) {
    return $QUERY;
  } else {
    return false;
  }
}

//fetch values 
function FETCH($SQL, $data, $die = false)
{
  if ($die == true) {
    SELECT($SQL, true);
  } else {
    $Query = SELECT($SQL);
    $CountData = mysqli_num_rows($Query);
    if ($CountData == null) {
      $results = 0;
    } else {
      $FetchDATA = mysqli_fetch_array($Query);
      $ReturnData = $FetchDATA["$data"];
      $results = $ReturnData;
    }
    return $results;
  }
}

//fetch all in array / json formate
function _DB_COMMAND_($sql, $array = false)
{
  $Data = SELECT("$sql");
  $Count = CHECK("$sql");
  if ($Count == 0) {
    return null;
  } else {
    while ($FetchAllData = mysqli_fetch_array($Data)) {
      $FetchedColumns[] = $FetchAllData;
    }

    if ($array == true) {
      return json_decode(json_encode($FetchedColumns));
    } else {
      return json_encode($FetchedColumns);
    }
  }
}

//get single values
function GET_DATA($tableName, $columnName, $conditions, $die = false)
{
  if ($die == true) {
    return SELECT("SELECT $columnName FROM $tableName WHERE $conditions", true);
  } else {
    $FetchedData = FETCH("SELECT $columnName FROM $tableName WHERE $conditions", "$columnName");
    if ($FetchedData == null) {
      $results = null;
    } else {
      $results = $FetchedData;
    }
    return $results;
  }
}

//Update function
function UPDATE($SQL, $die = false)
{
  $Update = "$SQL";
  //die entry
  if ($die == true) {
    die($Update);
  }
  $Query = mysqli_query(DBConnection, $Update);
  if ($Query == true) {
    return true;
  } else {
    return false;
  }
}


//update table 
function UPDATE_TABLE($sqltables, array $colums, $conditions, $die = false)
{
  $AvalableArrays = count($colums) - 1;
  $Columns = "";
  $countkeys = 0;
  // echo "<br><b style='color:green;'>• REQUESTING </b> -> <b>[$sqltables]</b> ---- <b style='color:green;'>Sent!</b> <br><b style='color:red'><i> Data Received</i></b> <b>[$sqltables]</b> @ [<br>";
  foreach ($colums as $key => $value) {
    $countkeys++;
    $$value = $value;
    global $$value;
    // echo "&nbsp;&nbsp; <b style='color:grey;'> Index:</b> $countkeys ( <b> " . $key . "</b> : " . $value . " ) <br>";
    if ($countkeys <= $AvalableArrays) {
      $Columns .= "$key='" . $value . "', ";
    } else {
      $Columns .= "$key='" . $value . "' ";
    }
  }

  //echo "]<br> ---<b style='color:primary;'>END</b><br><hr>---";
  $SQL = "UPDATE $sqltables SET $Columns where $conditions";

  $Update = UPDATE($SQL);

  //die entry
  if ($die == true) {
    UPDATE($SQL, true);
  }

  if ($Update == true) {
    return true;
  } else {
    return false;
  }
}

function CallID($length = 6)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';

  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }

  return strtoupper($randomString);
}

//data encryption and decryptions
function SECURE($string, $action = 'e')
{
  // you may change these values to your own
  $secret_key = 'my_simple_secret_key';
  $secret_iv = 'my_simple_secret_iv';

  $output = false;
  $encrypt_method = "AES-256-CBC";
  $key = hash('sha256', $secret_key);
  $iv = substr(hash('sha256', $secret_iv), 0, 16);

  if ($action == 'e' || $action == "E") {
    $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
  } else if ($action == 'd' || $action == "D") {
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
  }

  return $output;
}
