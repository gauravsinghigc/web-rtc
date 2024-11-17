<script>
  <?php if (!isset($_GET['room'])) { ?>

    function StartCallSession() {
      $.ajax({
        url: 'handler/create_call.php',
        method: 'POST',
        data: {
          call_user_name: "<?php echo CallID(5); ?>",
          call_sdp_offer_for_2: "" + document.getElementById("OfferId").value + "",
        },
        success: function(response) {
          var responseData = JSON.parse(response);

          if (responseData.status == "true") {
            document.getElementById("create-offer").innerHTML = "";
            document.getElementById("create-offer").classList.remove("btn");
            document.getElementById("create-offer").classList.remove("btn-sm");
            document.getElementById("create-offer").classList.remove("btn-success");
          }
        },
      });
    }
  <?php } ?>
</script>