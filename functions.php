<?php

function Start(){
global $maxx, $maxy;
		if ($_SESSION['placementdone'] == false)
		{
			$maxx = 5;
			$maxy = 5;
		}

		switch($_GET["action"])
				{ //do functions depending on buttons
			case "Console":
				$_SESSION["inputtype"] = "Console";
				break;
			case "Form":
				$_SESSION["inputtype"] = "Form";
				break;
			case "Reset":
				ResetRobot();
				break;
			case "Update":
				UpdateTableSize((int)$_GET["GridX"],(int)$_GET["GridY"]);
				break;
			case "Report":
				DoReport();	
				break;
				}

		if ($_SESSION["inputtype"] == "Form") //if using the form input
		{
			switch($_GET["action"])
			{ //do functions depending on buttons in form
			case "Place":
				DoPlace($_GET["input_x"], $_GET["input_y"]);
				break;
			case "LEFT":
				TurnLeft();
				break;
			case "RIGHT":
				TurnRight();
				break;
			case "MOVE":
				DoMove();
				break;		
			}	
		}
		else if($_SESSION["inputtype"] == "Console") //using console
		{
		
			switch (explode(" ",$_GET["inputcommand"])[0])
			{
				case "PLACE":
				try{
					DoPlace((int)explode(",",explode(" ",$_GET["inputcommand"])[1])[0],(int)explode(",",explode(" ",$_GET["inputcommand"])[1])[1]); //split up the command to get arguments X Y
					SetDirection(explode(",",explode(" ",$_GET["inputcommand"])[1])[2]); //split up the command to get the direction
				}
				catch (Exception $e)
				{
					Error($e->getMessage());	
				}
				
				break;
				case "MOVE":
				if ($_SESSION['placementdone'] == true)
				{	
				DoMove();
				}else {echo 'The robot must be placed first.';}
				break;
				case "LEFT":
				if ($_SESSION['placementdone'] == true)
				{
					TurnLeft();
				}else {echo 'The robot must be placed first.';}
				break;
				case "RIGHT":
				if ($_SESSION['placementdone'] == true)
				{
				TurnRight();
				}else {echo 'The robot must be placed first.';}
				case "REPORT":
				DoReport();
				case "HELP":
				DoHelp();
				break;
			}
		
		}
		
		DoButtons(); //setup buttons
		
		
		if ($_SESSION['placementdone'] == true)
		{
		DoGraphics();
		}
		
		
		}
function DoHelp()
{
echo '<p>type "PLACE (x),(y),(f)" = to place the robot at X Y and facing direction ex. NORTH, SOUTH, NORTHEAST, WEST.<br>';
echo 'type "MOVE" to move the robot forward 1 space.<br>';
echo 'type "LEFT" to rotate the robot direction anti-clockwise.<br>';
echo 'type "RIGHT" to rotate the robot direction clockwise.<br>';
echo 'type "REPORT" to print the current position and facing direction of the robot.<br>';
}
function UpdateTableSize($newgridX, $newgridY)
{
	global $maxx, $maxy;
	$maxx = $newgridX;
	$maxy = $newgridY;
}
function DoReport()
{
	echo 'The robot is currently facing <strong>'. GetDirection() . '</strong> at coordinates: ' . $_SESSION["x"] .'.'. $_SESSION["y"];

}
function SetDirection($direction_name)
{
echo '<br>'.$direction_name.'</br>';
	switch ($direction_name)
	{	
		case "NORTH":
		$_SESSION["dir"] = 0;
		break;
		case "NORTHEAST":
		$_SESSION["dir"] = 1;
		break;
		case "NORTHWEST":
		$_SESSION["dir"] = 7;
		break;
		case "SOUTH":
		$_SESSION["dir"] = 4;
		break;
		case "SOUTHEAST":
		$_SESSION["dir"] = 3;
		break;
		case "SOUTHWEST":
		$_SESSION["dir"] = 5;
		break;
		case "EAST":
		$_SESSION["dir"] = 3;
		break;
		case "WEST":
		$_SESSION["dir"] = 6;
		break;
		default:
		Error("Invalid Direction Input.");
		break;
	}
}

function DoGraphics() //display a table with the robot at coordinate
{
	global $maxx, $maxy;
		
	echo '<table style="width:35%;height:45%;">';
	for ($x = ($maxx); $x >= 0; $x--)
	{
		echo '<tr>';
		for ($y = 0; $y <= ($maxx); $y++)
		{
			echo '<td>';
			echo $y . '.';
			echo $x;
			DoGraphics_Robot($y, $x);
			echo '</td>';
		}
		echo '</tr>';	
	}
	echo '</table>';
	
}

function DoGraphics_Robot($x, $y) //this is executed inside of each table cell, it will first check if the robot is present at the cells coordinate, and return the robot image if true.
{
	if ($x == $_SESSION['x'] && $y == $_SESSION['y'])
	{
		echo '<img src="' . DoGraphics_GetRobotImage() . '">';
	}
}

function DoGraphics_GetRobotImage() //return the robot image depending on the direction facing
{
	switch($_SESSION['dir'])
	{
		case 0:
		return "images/north.jpg";
		break;
		case 1:
		return "images/northeast.jpg";
		break;
		case 2:
		return "images/east.jpg";
		break;
		case 3:
		return "images/southeast.jpg";
		break;
		case 4:
		return "images/south.jpg";
		break;
		case 5:
		return "images/southwest.jpg";
		break;
		case 6:
		return "images/west.jpg";
		break;
		case 7:
		return "images/northwest.jpg";
		break;
	}
}

function DoButtons(){ //use form buttons, or console text box
	echo '<form action="index.php" method="get">';
	global $maxx, $maxy;
	
	echo '<input type="submit" name="action" value="Console"> <input type="submit" name="action" value="Form"><br><br><br> ';
	
	if ($_SESSION["inputtype"] == "Form")
	{
	
		echo 	'<br><input type="text" value="X" name="input_x" size="3">
		<input type="text" value="Y" name="input_y" size="3">
		<input type="submit" value="Place" name="action">
		<br><br>
		';
	
		if ($_SESSION['placementdone'] == true)
		{
		echo '
			<input type="submit" value="LEFT" name="action">
			<input type="submit" value="MOVE" name="action">
			<input type="submit" value="RIGHT" name="action"> 
		';
		}else
		{
		echo '
			<input type="submit" value="LEFT" name="action" disabled>
			<input type="submit" value="MOVE" name="action" disabled> 
			<input type="submit" value="RIGHT" name="action" disabled> 
		';
		}
	}
	else if ($_SESSION["inputtype"] == "Console")
	{
		echo 'Command: <input type="text" name="inputcommand" value="type HELP for commands"> <input type="submit" name="Run" value="Run">';
	}
	
	
	
	
	echo '<br><br>
	<input type="submit" value="Report" name="action">
	<input type="submit" value="Reset" name="action">
	';
	echo '<strong> <br><br>Table-top Size: <input type="text" value="'.$maxx.'" name="GridX"size="2"><input type="text" value="'.$maxy.'" name="GridY" size="2"> <input type="submit" value="Update" name="action"><br></form>';
	


}
function IsValidCoordinate($inputcoordinate, $isY) //check the input coordinate is within the bounds of the table size
{	
	global $minx, $maxx, $miny, $maxy; //get global variables
	
	if ($isY == true)
	{
		if ($inputcoordinate >= $miny && $inputcoordinate <= $maxy)
		{
			return true;
		} else {return false;}
	}
	else
	{
	if ($inputcoordinate >= $minx && $inputcoordinate <= $maxx)
	{
		return true;
	} else {return false;}
	}
}




function DoMove() //move forward one cell, if destination is valid
{	
	if (IsValidCoordinate(GetDestination("X"), false) == true && IsValidCoordinate(GetDestination("Y"), true) == true) //check if the robot will not fall off the table after processing move.
	{
		echo 'The robot has moved from coordinates: ['. $_SESSION['x'] .'.'. $_SESSION['y'] . '] to position [' . GetDestination("X") . '.' . GetDestination("Y") . '] successfully.';
		$_SESSION['x'] = GetDestination("X");
		$_SESSION['y'] = GetDestination("Y");
	}else
	{
	
	echo 'The robot will fall!';
	
	}
}


function GetDestination($CoordinateXorY) //Returns the destination coordinate, given the current position, depending on string input X or Y 
{
	$directionfacing = $_SESSION['dir'];
	
	$current_x = $_SESSION['x'];
	$current_y = $_SESSION['y'];
	$destination_x = $current_x;
	$destination_y = $current_y;
	
	switch ($directionfacing)
	{	
		case 0:
		$destination_x = $destination_x;
		$destination_y = $destination_y + 1;
		break;
		case 1:
		$destination_x = $destination_x + 1;
		$destination_y = $destination_y + 1;
		break;
		case 2:
		$destination_x = $destination_x + 1;
		$destination_y = $destination_y;
		break;
		case 3:
		$destination_x = $destination_x + 1;
		$destination_y = $destination_y - 1;
		break;
		case 4:
		$destination_x = $destination_x;
		$destination_y = $destination_y - 1;
		break;
		case 5:
		$destination_x = $destination_x - 1;
		$destination_y = $destination_y - 1;
		break;
		case 6:
		$destination_x = $destination_x - 1;
		$destination_y = $destination_y;
		break;
		case 7:
		$destination_x = $destination_x - 1;
		$destination_y = $destination_y + 1;
		break;
	}
	
	if ($CoordinateXorY == "X")
	{
		return $destination_x;
	}
	else
	{
		return $destination_y;
	}
}

function TurnLeft()
{
	if ($_SESSION["dir"] == 0)
	{	
		$_SESSION["dir"] = 7;
		UpdateDirection();
	}
	else
	{
		$_SESSION["dir"] = $_SESSION["dir"]-1;
		UpdateDirection();
	}
	
}
function TurnRight()
{
	if ($_SESSION["dir"] == 7)
	{	
		$_SESSION["dir"] = 0;
		UpdateDirection();
	}
	else
	{
		$_SESSION["dir"] = $_SESSION["dir"]+1;
		UpdateDirection();
	}
}

function UpdateDirection()
{	
	echo 'Direction updated';	
}

function Move()
{
	
}


function ResetRobot() //reset session data
{	
	session_destroy();
	echo 'The robot has been reset';
}

function DoPlace($input_x, $input_y)
{	
	if ($_SESSION['placementdone'] == true)	//Check if the robot has been placed yet
	{	
		echo 'The robot has already been placed.';
	}
	else
	{
		if (is_numeric($input_x) && is_numeric($input_y)) //Check coordinate input is numeric
			{
				if ($input_x <= 5 && $input_x >=0 && $input_y >= 0 && $input_y <= 5) //Check valid coordinates
					{
					$_SESSION['placementdone'] = true;
					$_SESSION['x'] = $input_x;
					$_SESSION['y'] = $input_y;
					echo 'The robot has been placed at' .$input_x. '.' . $input_y .''; 
					}
					else {echo Error('You can only place the robot between X and Y coordinates 0-5');}
					
				
			}
			else {echo Error('Only numbers can be used for the X and Y coordinate.');}
			
	}
}

function Error($msg){ //Do an error echo
echo ' <font color="red"><strong>[ERROR]</font>' . $msg . '</font></strong>';

}

function GetDirection(){ 

	switch ($_SESSION["dir"])
	{	
		case 0:
		return "North";
		break;
		case 1:
		return "North East";
		break;
		case 2:
		return "East";
		break;
		case 3:
		return "South East";
		break;
		case 4:
		return "South";
		break;
		case 5:
		return "South West";
		break;
		case 6:
		return "West";
		break;
		case 7:
		return "North West";
		break;
	}
}
?>


