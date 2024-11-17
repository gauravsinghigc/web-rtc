let localStream;
let remoteStream1; // Remote stream for user 1 (offerer)
let remoteStream2; // Remote stream for user 2 (answerer)
let peerConnection1; // RTCPeerConnection for user 1
let peerConnection2; // RTCPeerConnection for user 2

let servers = {
  iceServers: [
    {
      urls: ["stun:stun1.1.google.com:19302", "stun:stun2.1.google.com:19302"],
    },
  ],
};

let init = async () => {
  localStream = await navigator.mediaDevices.getUserMedia({
    video: true,
    audio: false,
  });

  document.getElementById("user-1").srcObject = localStream;
};

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

let createAnswer = async () => {
  peerConnection2 = new RTCPeerConnection(servers);

  remoteStream2 = new MediaStream();
  document.getElementById("user-2").srcObject = remoteStream2;

  localStream.getTracks().forEach((track) => {
    peerConnection2.addTrack(track, localStream);
  });

  peerConnection2.ontrack = async (event) => {
    event.streams[0].getTracks().forEach((track) => {
      remoteStream2.addTrack(track);
    });
  };

  peerConnection2.onicecandidate = async (event) => {
    if (event.candidate) {
      document.getElementById("AnswerId").value = JSON.stringify(
        peerConnection2.localDescription
      );
    }
  };

  let offer = document.getElementById("OfferId").value;

  if (!offer) alert("Retrieve Offer from peer First!...");

  offer = JSON.parse(offer);
  await peerConnection2.setRemoteDescription(offer);

  let answer = await peerConnection2.createAnswer();
  await peerConnection2.setLocalDescription(answer);

  document.getElementById("AnswerId").value = JSON.stringify(answer);
};

let addAnswer = async () => {
  let answer = document.getElementById("AnswerId").value;
  if (!answer) alert("Retrieve Answer from peer First!...");

  answer = JSON.parse(answer);

  // Check if peerConnection1 is defined before accessing its properties
  if (peerConnection1) {
    if (!peerConnection1.currentRemoteDescription) {
      peerConnection1.setRemoteDescription(answer);
    }
  } else {
    console.error("peerConnection1 is not defined.");
  }
};

init();

document.getElementById("create-offer").addEventListener("click", createOffer);
document
  .getElementById("create-answer")
  .addEventListener("click", createAnswer);
document.getElementById("add-answer").addEventListener("click", addAnswer);
