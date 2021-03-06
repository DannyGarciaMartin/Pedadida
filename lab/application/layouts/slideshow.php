<!--
 *  Slimey - SLIdeshows Microformats Editor, part of the Feng Office weboffice suite - http://www.fengoffice.com
 *  Copyright (C) 2007 Ignacio de Soto
 *
 *  Slideshows engine
-->

<html>
<head>
<title>Feng Office Slideshow</title>
<style type="text/css">
body {
	margin: 0px;
	padding: 0px;
	background-color: black;
	overflow: hidden;
}
body * {
	line-height: 1;
}
h1 {
	font-size: 140%;
}
ul, ol {
	padding-left: 8%;
	margin-left: 0px;
}
#presentation {
	padding: 0px;
	margin: 0px;
	position: absolute;
	width: 1024px;
	height: 768px;
	overflow: hidden;
	background-color: white;
	inline: 3px solid red;
}
.slide, .hidden {
	padding: 0px;
	margin: 0px;
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	overflow: hidden;
	background-color: white;
}
.hidden {
	visibility: hidden;
}
</style>
<script>

var isIE = navigator.appName == 'Microsoft Internet Explorer' ? 1 : 0;
var isOp = navigator.userAgent.indexOf('Opera') > -1 ? 1 : 0;
var isGe = navigator.userAgent.indexOf('Gecko') > -1 && navigator.userAgent.indexOf('Safari') < 1 ? 1 : 0;

var slides = new Array();
var current;

function init() {
	var divs = document.getElementsByTagName('div');
	var num = 0;
	for (var i=0; i < divs.length; i++) {
		if (divs[i].className == 'slide') {
			divs[i].onclick = click;
			divs[i].className = 'hidden';
			slides[num++] = divs[i];
		}
	}
	current = 0;
	slides[current].className = 'slide';
	transition = fade;
	resize();
}

var transitionValue;

function none(from, to) {
	transitionValue = 110;
}

function scrollV(from, to) {
	if (transitionValue <= 100) {
		if (from <= to) {
			slides[from].style.top = "-" + transitionValue + "%";
			slides[to].style.top = (100 - transitionValue) + "%";
		} else {
			slides[to].style.top = "-" + (100 - transitionValue) + "%";
			slides[from].style.top = transitionValue + "%";
		}
	} else {
		slides[from].style.top = 0;
		slides[to].style.top = 0;
	}
}

function scrollH(from, to) {
	if (transitionValue <= 100) {
		if (from <= to) {
			slides[from].style.left = "-" + transitionValue + "%";
			slides[to].style.left = (100 - transitionValue) + "%";
		} else {
			slides[to].style.left = "-" + (100 - transitionValue) + "%";
			slides[from].style.left = transitionValue + "%";
		}
	} else {
		slides[from].style.left = 0;
		slides[to].style.left = 0;
	}
}

function fade(from, to) {
	if (transitionValue <= 100) {
		slides[from].style.filter = "alpha(opacity=" + (100 - transitionValue) + ")";
		slides[to].style.filter = "alpha(opacity=" + transitionValue + ")";
		slides[from].style.opacity = ((100 - transitionValue) / 100);
		slides[to].style.opacity = (transitionValue / 100);
	} else {
		slides[from].style.filter = "alpha(opacity=0)";
		slides[to].style.filter = "alpha(opacity=100)";
		slides[from].style.opacity = "0";
		slides[to].style.opacity = "1";
	}
}

function transitionLoop(from, to) {
	if (transitionValue <= 100) {
		transition(from, to);
		transitionValue += 10;
		setTimeout('transitionLoop(' + from + ', ' + to + ')', 100);
	} else {
		if (from != to) {
			slides[from].className = 'hidden';
		}
		transition(from, to);
		current = to;
		inTransition = false;
	}
}

function transitionStart(from, to) {
	inTransition = true;
	transitionValue = 0;
	slides[to].className = 'slide';
	transitionLoop(from, to);
}

var transition;
var inTransition = false;

function click() {
	if (inTransition) {
		return;
	}
	if (current < slides.length - 1) {
		transitionStart(current, current + 1);
	}
}

function keyUp(e) {
	if (inTransition) {
		return;
	}
	if (!e) {
		e = event;
		e.which = e.keyCode;
	}
	if (e.which == 37) {
		// left
		if (current > 0) {
			transitionStart(current, current - 1);
		}
	} else if (e.which == 39) {
		if (current < slides.length - 1) {
			transitionStart(current, current + 1);
		}
	}
}

function resize() {
	var hScale = 32;
	var vScale = 24;
	var ratio = 4 / 3;
	if (window.innerHeight) {
		var hSize = window.innerWidth;
		var vSize = window.innerHeight;
	} else if (document.documentElement.clientHeight) {
		var hSize = document.documentElement.clientWidth;
		var vSize = document.documentElement.clientHeight;
	} else if (document.body.clientHeight) {
		var hSize = document.body.clientWidth;
		var vSize = document.body.clientHeight;
	} else {
		var hSize = 1024;
		var vSize = 768;
	}
	var newSize = Math.min(Math.round(vSize / vScale),Math.round(hSize / hScale));
	
	var div = document.getElementById('presentation');
	div.style.fontSize = newSize + 'px';
	if (hSize / vSize >= ratio) {
		div.style.height = vSize + 'px';
		div.style.top = '0px';
		div.style.width = vSize * ratio + 'px';
		div.style.left = (hSize - vSize * ratio) / 2 + 'px';
	} else {
		div.style.width = hSize + 'px';
		div.style.left = '0px';
		div.style.height = hSize / ratio + 'px';
		div.style.top = (vSize - hSize / ratio) / 2 + 'px';
	}
}


window.onload = init;
window.onresize = resize;
document.onkeyup = keyUp;
</script>
</head>

<body>

<div id="presentation">
<?php
	if ($error) {
?>
		<div class="slide" style="background-color: white; text-align: center; color: red;">Error: <?php echo $error ?></div>
<?php
	} else {
		echo $content;
	}
?>
	<div class="slide" style="background-color: black; text-align: center; color: white;">End of slideshow</div>
</div>


</body>
</html>