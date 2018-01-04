var origin = document.getElementById("driver_start").value;
var destination = document.getElementById("driver_end").value;
console.log(origin)
var source =
"https://www.google.com/maps/embed/v1/directions?origin=" +
origin +
"&destination=" +
destination +
"&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY";
$(function() {
$("#googleMap").attr("src", source);
});
