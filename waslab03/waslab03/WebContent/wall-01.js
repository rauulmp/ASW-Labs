var baseURI = "http://localhost:8086/waslab03";
var tweetsURI = baseURI+"/tweets";

var req;
var tweetBlock = "	<div id='tweet_{0}' class='wallitem'>\n\
	<div class='likes'>\n\
	<span class='numlikes'>{1}</span><br /> <span\n\
	class='plt'>people like this</span><br /> <br />\n\
	<button onclick='{5}Handler(\"{0}\")'>{5}</button>\n\
	<br />\n\
	</div>\n\
	<div class='item'>\n\
	<h4>\n\
	<em>{2}</em> on {4}\n\
	</h4>\n\
	<p>{3}</p>\n\
	</div>\n\
	</div>\n";

String.prototype.format = function() {
	var args = arguments;
	return this.replace(/{(\d+)}/g, function(match, number) { 
		return typeof args[number] != 'undefined'
			? args[number]
		: match
		;
	});
};

function likeHandler(tweetID) {
	var target = 'tweet_' + tweetID;
	var uri = tweetsURI+ "/" + tweetID +"/likes";
	// e.g. to like tweet #6 we call http://localhost:8080/waslab03/tweets/6/like
	
	req = new XMLHttpRequest();
	req.open('POST', uri, /*async*/true);
	req.onreadystatechange = function() {
		if (req.readyState == 4 && req.status == 200) {
			document.getElementById(target).getElementsByClassName("numlikes")[0].innerHTML = req.responseText;
		}
	};
	req.send(/*no params*/null);
}

function deleteHandler(tweetID) {
	/*

* TASK #4 
	
	*/	
	req = new XMLHttpRequest();
	req.open('DELETE', tweetsURI+ "/" + tweetID + "?token=" + localStorage.getItem(tweetID), /*async*/true);
	req.onreadystatechange = function() {
		if (req.readyState == 4 && req.status == 200) {
			var twt = document.getElementById("tweet_"+tweetID);
			twt.parentNode.removeChild(twt);
			localStorage.removeItem(tweetID);
			
		}
	};
	req.send(/*no params*/null);
}

function getTweetHTML(tweet, action) {  // action :== "like" xor "delete"
	var dat = new Date(tweet.date);
	var dd = dat.toDateString()+" @ "+dat.toLocaleTimeString();
	return tweetBlock.format(tweet.id, tweet.likes, tweet.author, tweet.text, dd, action);
	
}

function getTweets() {
	req = new XMLHttpRequest(); 
	req.open("GET", tweetsURI, true); 
	req.onreadystatechange = function() {
		if (req.readyState == 4 && req.status == 200) {
			var tweet_list = req.responseText;
			/*
* TASK #2 -->
			*/
			var twts = JSON.parse(tweet_list);
			var twtsToHtml = "";
			for(var i = 0; i < twts.length;++i){
				var tt = twts[i];
				//mirem si el tweet esta a la localStorage per posar "delete" en cas afirmatiu
				if (localStorage.getItem(tt.id)!= null) twtsToHtml += getTweetHTML(tt, "delete");
				else twtsToHtml += getTweetHTML(tt, "like");
			}
			document.getElementById("tweet_list").innerHTML = twtsToHtml;
		};
	};
	req.send(null); 
};


function tweetHandler() {
	var author = document.getElementById("tweet_author").value;
	var text = document.getElementById("tweet_text").value;
	/*
* TASK #3 -->
				*/ 
	req = new XMLHttpRequest();
	req.open('POST', tweetsURI, /*async*/true);
	req.onreadystatechange = function() {
		if (req.readyState == 4 && req.status == 200) {
			var twt = req.responseText;
			var nt = JSON.parse(twt);
			var ntHTML = getTweetHTML(nt, "delete");
			document.getElementById("tweet_list").innerHTML = ntHTML + document.getElementById("tweet_list").innerHTML;
			
			//afegim id i token al localStorage
			if (typeof(Storage) !== "undefined") {
				localStorage.setItem(nt.id, nt.token);
			} else {
			    // Local storage no suportat
			}
		}
	};
	req.setRequestHeader("Content-Type","application/json");
	var stringJson = JSON.stringify({ aut: author, txt: text });
	req.send(stringJson);
	
	// clear form fields
	document.getElementById("tweet_author").value = "";
	document.getElementById("tweet_text").value = "";

};

//main
function main() {
	document.getElementById("tweet_submit").onclick = tweetHandler;
	getTweets();
};
