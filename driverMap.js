var filePath = "user.txt";
var userData;

function driverMap() {
  var name = document.getElementById("driver_username");
  var origin = document.getElementById("driver_start").value;
  var destination = document.getElementById("driver_end").value;
  var time = document.getElementById("driver_time");
  var seats = document.getElementById("driver_seats");
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
//   editData();
}

function editDta() {
  var newData = readData();
  newData =
    newData +
    "/n" +
    name +
    "," +
    origin +
    "," +
    destination +
    "," +
    time +
    "," +
    seats +
    ".";
  writeData(newData);
}

function writeData(newData) {
  var fso = new ActiveXObject("Scripting.FileSystemObject");
  var fh = fso.OpenTextFile(filePath);
  fh.WriteLine(newData);
  fh.Close();
}

function readData() {
  var fso = new ActiveXObject("Scripting.FileSystemObject");
  var fh = fso.OpenTextFile(filePath, 1, false, 0);
  var lines = "";
  while (!fh.AtEndOfStream) {
    lines += fh.ReadLine() + "\r";
  }
  fh.Close();
  return lines;
}
