<html>
<head>
<style>
pre {
background-color: #EEE;
padding: 20px;
}
@media (min-width:320px) {
html {
padding: 0px 10%;
}

}

</style>
</head>
<body>
<p><br>
<center>
	<img src="https://xonotic.org/static/img/xonotic-logo.png"><br>
<div style="padding: 20px; background-color: #EEFFEE; font-size: 14pt; display: inline-block; font-weight: bold">
Hi. This is just some 3 Euro VPS from OVH which I use as Xonotic server. It seems to handle at least 16 players. <p>
Check out <a href="https://xonotic.org">Xonotic</a> and <a href="https://ballerburg.us.to">my website</a>.<p>
</div><p>
OVH offers the following locations:
<div style="display: inline-block"><pre>
North America, Canada, Beauharnois (BHS)
Pacific Asia, Australia, Sydney (SYD)
Western Europe, France, Gravelines (GRA)
Western Europe, France, Strasbourg (SBG)
Central Europe, Poland, Warsaw (WAW)
Central Europe, Germany, Frankfurt (DE)
Western Europe, United Kingdom, London (UK)
</pre></div><p>
The dynamic DNS provider I use to get a "us.to" subdomain is <a href="http://afraid.org">Afraid.org</a> (this is not required).
</center>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require("Parsedown.php");
$Parsedown = new Parsedown();
//$Parsedown->setStrictMode(false);
echo $Parsedown->text(file_get_contents("README.md"));
?>
</body>
