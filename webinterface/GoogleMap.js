var origin = window.prompt( "Please enter your starting point", "Vancouver" );
var destination = window.prompt( "Please enter your destination point", "BCIT" );
var source = "https://www.google.com/maps/embed/v1/directions?origin=" + origin + "&destination=" + destination +   "&key=AIzaSyAh-wxnCsW7OZsqkWMHXLFtdjwLXo1PsqY";
$(function(){
    $("#googleMap").attr('src', source);   
});
