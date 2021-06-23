// var video = document.querySelector("#videoFeed");

// if (navigator.mediaDevices.getUserMedia) {
//   navigator.mediaDevices.getUserMedia({ video: true })
//     .then(function (stream) {
//       video.srcObject = stream;
//     })
//     .catch(function (err0r) {
//       console.log("Something went wrong!");
//     });
// } 
var videoRunning = false;
var filterElement = document.getElementById("positionnedFilter");
var videoElement = document.getElementById("videoFeed");
var picElement = document.getElementById("outputImage");
var insertImageButton = document.getElementById("insertImageButton");
videoElement.style.display = "none";
insertImageButton.style.display = "block";
picElement.style.display = "block";

window.addEventListener("load", function(){
  // ASK FOR USER PERMISSION TO ACCESS CAMERA AFTER CHECKING IF THE CAMERA WORKS

  document.getElementById("picUp").onclick = picUp;

  navigator.mediaDevices.getUserMedia({
    //  THE EASY WAY
    video: true

    // TO SPECIFY PREFERRED RESOLUTION
    // video: {
    //   width: { min: 852, ideal: 1280, max: 1920 },
    //   height: { min: 480, ideal: 720, max: 1080 }
    // }
  })

  // ON GETTING CAMERA ACCESS
  .then(function(stream) {
    //  STREAM WEBCAM TO VIDEO TAG
    var video = document.getElementById("videoFeed");
    video.srcObject = stream;
    video.play();
    videoRunning = true;
    videoElement.style.display = "block";
    picElement.style.display = "none";
    insertImageButton.style.display = "none";
    // ENABLE DEMO BUTTONS
    // document.getElementById("picTake").onclick = picTake;
    // document.getElementById("picDown").onclick = picDown;
  })

  // FAILURE - NO WEBCAM ATTACHED AND/OR NO PERMISSION
  .catch(function(err) {
    alert("Please enable access and/or attach a webcam");
  });
});

function preview_image(event) 
{
var reader = new FileReader();
  reader.onload = function()
  {
    var output = document.getElementById('outputImage');
    output.src = reader.result;
  }
  reader.readAsDataURL(event.target.files[0]);
}

function picCreate(canvas) {
  var side = videoRunning ? document.getElementById("videoFeed").clientWidth : document.getElementById("outputImage").clientWidth,
    image = videoRunning ? document.getElementById("videoFeed") : document.getElementById("outputImage"),
    context2D = canvas.getContext("2d");
  canvas.width = side;
  canvas.height = side;
  context2D.drawImage(image, 0, 0, side, side);
}

function picUp () {
  if (filterElement.src && (videoElement.style.display == "block" || (picElement.style.display == "block" && picElement.src)))
  {
    // CREATE SNAPSHOT FROM VIDEO
    var canvas = document.createElement("canvas"),
      elementWidth = videoRunning ? document.getElementById("videoFeed").clientWidth : document.getElementById("outputImage").clientWidth,
      elementHeight = videoRunning ? document.getElementById("videoFeed").clientHeight : document.getElementById("outputImage").clientHeight,
      image = videoRunning ? document.getElementById("videoFeed") : document.getElementById("outputImage"),
      context2D = canvas.getContext("2d"),
      filterWidth = document.getElementById("positionnedFilter").clientWidth,
      filterHeight = document.getElementById("positionnedFilter").clientHeight;

    canvas.width = elementWidth;
    canvas.height = elementHeight;
    context2D.drawImage(image, 0, 0, elementWidth, elementHeight);
    context2D.drawImage(filterElement, (elementWidth / 2) - (filterWidth / 2), (elementHeight / 2) - (filterHeight / 2), filterWidth, filterHeight);


    // CONVERT TO BLOB + UPLOAD
    canvas.toBlob(function(blob){
      // FORM DATA
      var fd = new FormData();
      fd.append('upimage', blob);

      // AJAX UPLOAD
      fetch('/upload.php', {method:"POST", body:fd})
      .then(response => {
        if (response.ok) return response;
        else throw Error(`Server returned ${response.status}: ${response.statusText}`)
      })
      .then(response => console.log(response.text()))
      .catch(err => {
        alert(err);
      });

    });
      setTimeout(function (){
        location.reload();      
      }, 1000);
      
  }
}

