<?php
require "../config.php";
if (isset($_GET['id'])) {
  $UserId = $_GET['id'];
  $_SESSION['id'] = $UserId;
}
$UserId = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Video Call App</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <section class="container p-3">
    <div class="row">
      <div class="col-md-12">
        <h4><b>User Account :</b> <?php echo $UserId; ?></h4>
      </div>
      <div class="col-md-12">
        <div class="video-call d-flex mx-auto">
          <div class="shadow-sm p-2">
            <video class="video1" id='user-1' autoplay playsinline></video>
            <video class="video2" id='user-2' autoplay playsinline></video>
          </div>
        </div>
        <hr>
        <div id='approvedCalls'>
          <textarea name="call_sdp_offer_for_2" placeholder="Offer" class="form-control form-control-sm m-1" id="OfferId"></textarea>
          <textarea id="AnswerId" name="call_sdp_answer_1" placeholder="Answer" class="form-control form-control-sm m-1"></textarea>

          <span id="create-offer" class="btn btn-md btn-success">Create Request</span>
          <span id="create-answer" class="btn btn-md btn-info">Approve Request</span>
          <span id="add-answer" class="btn btn-md btn-primary">Start Call</span>
        </div>
        <div id='pendingCalls'>
          <h2 class="text-center"><b>SOME ONE CALLING...</b></h2>
          <center>
            <button id='ApproveNewCall' class="btn btn-sm btn-success">Receive</button>
            <button id='RejectCall' class="btn btn-sm btn-danger">Reject</button>
          </center>
        </div>
      </div>
    </div>
  </section>
</body>
<script src="../handle.js"></script>
<script>
  function StartCallSession() {
    $.ajax({
      url: 'handler/create_call.php',
      method: 'POST',
      data: {
        call_to: "<?php echo $UserId; ?>",
        call_sdp_offer: "" + document.getElementById("OfferId").value + "",
        SendCallRequest: "true",
      },
      success: function(response) {
        var responseData = JSON.parse(response);

        if (responseData.status == "true") {

        }
      },
    });
  }

  //always check for any sender who wants to connect a call
  function checkUpdates() {
    $.ajax({
      url: '../handler/response.php',
      method: 'POST',
      data: {
        call_to: '<?php echo $UserId; ?>',
        CheckForAnyPendingCalls: "true"
      },
      success: function(response) {
        var responseData = JSON.parse(response);
        if (responseData.call_recived == "true") {
          document.getElementById("approvedCalls").style.display = "none";
          document.getElementById("pendingCalls").style.display = "block";
        } else {
          document.getElementById("approvedCalls").style.display = "block";
          document.getElementById("pendingCalls").style.display = "none";
        }

      },
    });
  }

  // Call checkUpdates every second
  setInterval(checkUpdates, 1000);

  //approve call 
  var ApproveNewCall = document.getElementById("ApproveNewCall");

  function ApproveNewCalls() {
    $.ajax({
      url: '../handler/response.php',
      method: 'POST',
      data: {
        call_to: '<?php echo $UserId; ?>',
        ApproveCalls: "true"
      },
      success: function(response) {
        var responseData = JSON.parse(response);
        if (responseData.approved == "true") {
          let createOffer = async () => {
            peerConnection1 = new RTCPeerConnection(servers);

            remoteStream1 = new MediaStream();
            document.getElementById("user-2").srcObject = remoteStream1;

            localStream.getTracks().forEach((track) => {
              peerConnection1.addTrack(track, localStream);
            });

            peerConnection1.ontrack = async (event) => {
              event.streams[0].getTracks().forEach((track) => {
                remoteStream1.addTrack(track);
              });
            };

            peerConnection1.onicecandidate = async (event) => {
              if (event.candidate) {
                document.getElementById("OfferId").value = JSON.stringify(
                  peerConnection1.localDescription
                );
              }
            };

            let offer = await peerConnection1.createOffer();
            await peerConnection1.setLocalDescription(offer);

            document.getElementById("OfferId").value = JSON.stringify(offer);
          };
        }
        createOffer();
      },
    });
  }
  ApproveNewCall.addEventListener("click", ApproveNewCalls);
</script>

</html>