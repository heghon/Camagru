var videoElement = document.getElementById("videoFeed");
var picElement = document.getElementById("outputImage");
var filterElement1 = document.getElementById("Cadre1");
var filterElement2 = document.getElementById("Cadre2");
var filterElement3 = document.getElementById("Cadre3");
var filterElement4 = document.getElementById("Cadre4");
var filterElement5 = document.getElementById("Cadre5");
var filterElement6 = document.getElementById("Cadre6");
var filterElement = document.getElementById("positionnedFilter");
    
function putFilter(number) {
    // console.log("filter" + number);
    id = "filter" + number;
    filterPath = document.getElementById(id).src;
    // console.log(filterPath);
    filterElement.src = filterPath;
}