// console.log(document.getElementsByClassName("deleteImageButton"));

function deleteImage(id) {
    // console.log(id);
    fetch('/deleteImage.php?id=' + id + '', {method:"GET"})
    .then(response => {
      if (response.ok) return response;
      else throw Error(`Server returned ${response.status}: ${response.statusText}`)
    })
    .then(response => console.log(response.text()))
    .catch(err => {
      alert(err);
    });
    
    setTimeout(function (){
        location.reload();      
    }, 1000);
}