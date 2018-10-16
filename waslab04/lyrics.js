
function showSongs () {
  var content = document.getElementById("content").value;

  // Replace the two lines below with your implementation
  var uri = "http://localhost:8080/waslab04/lyric_search.php";
  var req = new XMLHttpRequest();
  req.open('GET', uri + "?content=" + content, true);
  req.onreadystatechange = function(){
    if(req.readyState == 4 && req.status == 200){
      var songs_list = req.responseText;
      var songs = JSON.parse(songs_list);
      var html = document.getElementById("left").innerHTML;
      for(var i = 0; i < songs.length-1; ++i){
        var name = songs[i].Song;
        var artist = songs[i].Artist;
        var lyricId = songs[i].LyricId;
        var lyricCheckSum = songs[i].LyricChecksum;
        html += "<p><a href='#' onClick = getLyric('"+lyricId+"','"+lyricCheckSum+"')>"+name+" ("+artist+")</a></p>";
      }
      document.getElementById("left").innerHTML = html;
      document.getElementById("right").innerHTML = "<-- Select a song from the left menu";
    }else{
     //document.getElementById("left").innerHTML = "<p>Error "+req.readyState+"</p>";
    }
  };
  req.send(null);

};


function getLyric (lyricId, lyricCheckSum) {
  var uri = "http://localhost:8080/waslab04/get_lyric.php";
  var req = new XMLHttpRequest();
  req.open('GET', uri + "?lyricId=" + lyricId + "&lyricCheckSum=" + lyricCheckSum, true);
  req.onreadystatechange = function() {
    if(req.readyState == 4 && req.status == 200){
      var resp = JSON.parse(req.responseText);
	resp.Lyric = (resp.Lyric).replace(/(\r\n|\n|\r)/gm, "<br />");
	document.getElementById("right").innerHTML = "<table width = 100%>" +
  "<tr>" +
    "<th><h2>" + resp.LyricSong + "</h2><br><h3>" + resp.LyricArtist + "</h3></th>" +
    "<th><img src="+ resp.LyricCovertArtUrl +"</img></th></tr>" +
  "<tr>" +
    "<td></td></tr>" +
    "<tr><td colspan='2'><p>" + resp.Lyric + "</p></td></tr>" +
    "</table>";
    }
  };
  req.send(null);
}

window.onload = showSongs();

