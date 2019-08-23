
<head>
	
	<style>
		*{ font-family: Tahoma;}
		table, th, td {
		padding: 15px;
  border: 1px solid black;
  border-collapse: collapse;
}
	</style>

</head>

<?php	session_start(); ?>


<?php
	//include functions
	include 'functions.php';
	
	//define variables for robot
	$x = $_SESSION["x"];
	$y = $_SESSION["y"];
	$dir = $_SESSION["dir"];
	
	$inputtype = $_SESSION["inputtype"];
	
	
	$placementdone = $_SESSION["placementdone"];
	$action = $_GET["action"]; // action of button clicked, or input command
	
	//define table size
	$minx = 0;
	$maxx = $_GET["GridX"];
	$miny = 0;
	$maxy = $_GET["GridY"];
	
	?>
	
	<center><h1>Toy Robot Simulator</h1>
	<?php 
	
	Start();
	
	echo "</strong>";
	
	
		
		echo "<br>The robot is currently located at <strong>[X=" . $_SESSION["x"] . "] [Y=" .$_SESSION["y"]. "]</strong> Facing Direction:<strong> ". GetDirection() . "<br>";
	
	?>