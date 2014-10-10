<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd"/>
<html>
<head>

	<title>Winery Search</title>
	<style>
	table
	{
		border: 1px solid black;
		padding: 50px;
		align: center;
		margin: auto;
	}
	
	th, td
	{
		padding: 25px;
	}
	</style>
        
	<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.15/angular.min.js"></script>
	
</head>
<body>
<form action="res2.php" method=get>
	<table>
	
		
		<tr>
			<th colspan="2" style="text-align:center; font-size:20px">
				Winery
			</th>
		</tr>
		
		<tr>
			<td>
				Wine Name
			</td>
			<td>
				<input type="text" name="winename"/>
			</td>
		</tr>
		
		<tr>
			<td>
				Region
			</td>
			<td>
				<?php
					$connection = mysqli_connect("localhost","root","","winestore");
					$result = mysqli_query ($connection,"SELECT region_name FROM region");
					echo '<select name="regions">';
					while ($row=mysqli_fetch_array($result))
					{
						echo "<option>".($row["region_name"])."</option>";
					}
					echo "</select>";
				?>
			</td>
		</tr>
		
		<tr>
			<td>
				Winery Name
			</td>
			<td>
				<input type="text" name="wineryname"/>
			</td>
		</tr>
		
		<tr>
			<td>
				Year
			</td>
			<td>
				<input type="number" min="1970" max = "1999" name="startyear" style="width: 65px"/>
				~
				<input type="number" min="1970" max = "1999" name="endyear" style="width: 65px"/>
			</td>
		</tr>
		
		<tr>
			<td>
				Minimum no. Stock
			</td>
			<td>
				<input type="number" min="0" max = "9999" name="minstock"/>
			</td>
		</tr>
		
		<tr>
			<td>
				Customers' No.:
			</td>
			<td>
				<input type="number" min="0" max = "9999" name="customerno"/>
			</td>
		</tr>
		
		<tr>
			<td>
				Price
			</td>
			<td>
				$<input type="number" min="0" max = "9999" name="minprice"/>
				~
				$<input type="number" min="0" max = "9999" name="maxprice"/>
			</td>
		</tr>
		
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="search"/>
			</td>
		</tr>
	</table>
</form>
</body>	
</html>
