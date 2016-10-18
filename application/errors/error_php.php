<html>
<head>
<title>PHP Error</title>
<style type="text/css">

body {
background-color:	#fff;
margin:				40px;
font-family:		Lucida Grande, Verdana, Sans-serif;
font-size:			11px;
color:				#000;
}

#content  {
color: 				#367fa9;
border:				0;
background-color:	#fff;
padding:			20px 20px 12px 20px;
}

h1 {
font-weight:		normal;
font-size:			18px;
color:				#999900;
margin:				0 0 4px 0;
}

a{
color: 				#367fa9;

}
</style>
</head>
<body>
	<div id="content">
		<h1>A PHP Error was encountered</h1>
		<p>Severity: <?php echo $severity; ?></p>
		<p>Message:  <?php echo $message; ?></p>
		<p>Filename: <?php echo $filepath; ?></p>
		<p>Line Number: <?php echo $line; ?></p>
	</div>
</body>
</html>
