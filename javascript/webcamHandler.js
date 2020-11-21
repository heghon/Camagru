(function () {
  'use strict';

  const video = document.getElementById("video");
  const canvas = document.getElementById("canvas");
  const snap = document.getElementById("snap");

  const constraints = {
    audio: true,
    audio: false
  };
  
  document.querySelector('button').addEventListener('click', async (e) => {
    const stream = await navigator.mediaDevices.getUserMedia({
      video: true
    })
  })
}) ();
