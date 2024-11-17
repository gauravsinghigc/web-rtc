<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>One to One Video Chat</title>
  <link rel="stylesheet" href="main.css">

</head>

<body>
  <section>
    <h3>Video Calling Applications</h3>

    <div id="videos">
      <video class="video-player" id="user-1" autoplay playsinline></video>
      <video class="video-player" id="user-2" autoplay playsinline></video>
    </div>

    <div id="content">
      <div>
        <br>
        <label>SDP Offer</label>
        <button id="create-offer">Create offer</button><br>
        <hr>
        <textarea id="offer-sdp" rows="15"></textarea><br>
      </div>
      <div>
        <br>
        <label>SDP Answer</label>
        <button id="create-answer">Create Answers</button><br>
        <hr>
        <textarea id="answer-sdp" rows="15"></textarea><br>
        <button id="add-answer">Add Answer</button>
      </div>
    </div>

  </section>
</body>
<script src="main.js"></script>

</html>