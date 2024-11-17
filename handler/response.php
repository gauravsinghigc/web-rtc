<?php
require "../config.php";

//start calls
if (isset($_POST['StartCall'])) {
  $calls = [
    "call_to" => $_POST['call_to'],
    "call_from" => $_POST['StartCall'],
    "call_status" => 1,
    "call_created_at" => date("Y-m-d h:i:s A"),
    "call_ref_no" => "#CALL" . date("dmyhis") . "" . rand(000000, 999999),
    "call_receiver_status" => "pending"
  ];
  $UpdateAllCalls = UPDATE("UPDATE video_calls SET call_status='0' where call_from='" . $_POST['StartCall'] . "'");
  $CheckCallStatus = CHECK("SELECT * FROM video_calls where call_from='" . $_POST['StartCall'] . "' and call_status='1'");
  if ($CheckCallStatus == null) {
    $Response = INSERT("video_calls", $calls);
    if ($Response == true) {
      header("location: ../call/?id=" . $_POST['StartCall'] . "");
    }
  } else {
    header("location: ../index.php?failed=Unable to Start Video Call at the moment!");
  }

  //check for any pending call and send response
} elseif (isset($_POST['CheckForAnyPendingCalls'])) {
  $call_to = $_POST['call_to'];

  $CheckForPendingCalls = CHECK("SELECT * FROM video_calls where call_to='$call_to' and call_status='1' and call_receiver_status='pending'");
  if ($CheckForPendingCalls != null) {
    $response['call_recived'] = "true";
  } else {
    $response['call_recived'] = "false";
  }

  echo json_encode($response);

  //approve calls
} elseif (isset($_POST['ApproveCalls'])) {
  $call_to = $_POST['call_to'];
  $UpdateAllCalls = UPDATE("UPDATE video_calls SET call_receiver_status='completed' where call_to='$call_to'");
  $UpdateAllCalls = UPDATE("UPDATE video_calls SET call_receiver_status='approved' where call_to='$call_to' ORDER BY calls_id DESC limit 1");
  if ($UpdateAllCalls == true) {
    $response['approved'] = "true";
  } else {
    $response['approved'] = "false";
  }

  echo json_encode($response);

  //send call request
} elseif (isset($_POST['SendCallRequest'])) {
  $call_to = $_POST['call_to'];
  $call_sdp_offer = $_POST['call_sdp_offer'];
  $video_calls = [
    "call_to" => $call_to,
    "call_sdp_offer" => $call_sdp_offer,
  ];
  $Update = UPDATE_TABLE("video_calls", $video_calls, "call_to='$call_to'");
  if ($Update == true) {
    $response['success'] = "true";
  } else {
    $response['failed'] = "false";
  }
  echo json_encode($response);
}
