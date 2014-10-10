<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd"/>
<html>
<head>
	<title>Search Result</title>
	<style>
	table
	{
		border: 1px solid black;
		padding: 5px;
		align: center;
		margin: auto;
	}
	
	th, td
	{
                text-align:center;
		padding: 15px;
	}
	</style>
</head>
<body>
	
<?php
	
	//Obtaining user input from FORM
	$wine = $_GET['winename'];
	$region = $_GET['regions'];
	$wineryname = $_GET['wineryname'];
	$yearstart = $_GET['startyear'];
	$yearend = $_GET['endyear'];
	$minimal = $_GET['minstock'];
	$customer = $_GET['customerno'];
	$minimalprice = $_GET['minprice'];
	$maximalprice = $_GET['maxprice'];
	
		
	//Validation if no input
	
	if($wine == NULL)
	{
		$wine = "";
	}
	
	if($wineryname == NULL)
	{
		$wineryname = "";
	}
	
	if($region == "All")
	{
		$region = "";
	}
	
	if($yearstart == NULL)
	{
		$yearstart = 1970;
	}
	
	if($yearend == NULL)
	{
		$yearend = 1999;
	}
	
	if($minimal == NULL)
	{
		$minimal = 0;
	}
	
	if($minimalprice == NULL)
	{
		$minimalprice = 0;
	}
	
	if($maximalprice == NULL)
	{
		$maximalprice = 1000;
	}
	
	if($customer == NULL)
	{
		$customer = 0;
	}
	
	//Year and Price validation
	if($yearstart > $yearend || $minimalprice > $maximalprice)
	{
		displayvalidationerror();
	}
	else
	{
		//query for all user input
		$query = "SELECT 
		wine_name, variety, year, winery_name, region_name, cost, on_hand, COUNT(items.cust_id) AS TotalCustomer
		FROM 
		wine, winery, items, region, inventory, grape_variety, wine_variety
		
		
		WHERE 
		wine.winery_id = winery.winery_id AND 
		winery.region_id = region.region_id AND 
		wine.wine_id = items.wine_id AND 
		wine.wine_id = inventory.wine_id AND 
		wine.wine_id = wine_variety.wine_id AND
		wine_variety.variety_id = grape_variety.variety_id AND 
		
		wine_name LIKE '%".$wine."%' AND 
		winery_name LIKE '%".$wineryname."%' AND 
		region_name LIKE '%".$region."%' AND 
		on_hand >= '".$minimal."' AND 
		(year BETWEEN '".$yearstart."' AND '".$yearend."') AND 
		(cost BETWEEN '".$minimalprice."' AND '".$maximalprice."')
		
		GROUP BY
		wine.wine_name, 
		grape_variety.variety,
		wine.year,
		winery.winery_name,
		region.region_name,
		inventory.cost,
		inventory.on_hand
		
		HAVING (TotalCustomer >= '".$customer."')
		";
	
		// Connect to the MySQL server
		$connection = mysqli_connect("localhost","root","","winestore");
		$result = mysqli_query ($connection, $query);

		// Display the results
		displaycorrectresults($result);
	}
	
	function displayvalidationerror()
	{
		print '<table>
		
		
                   <tr>
			<th colspan="2" style="font-size:20px">
				Search Result
			</th>
                    </tr>
		
                    <tr>
			<td colspan ="2">I am sorry</td>
                    </tr>
		
                    <tr>
			<td colspan="2"> Please ensure your minimum year or price is lesser than the maximum</td>
                    </tr>
            </table>';
	}
	
	//showing the wines result in tables that is correct
	function displaycorrectresults($result)
	{
		//table 1
		if(mysqli_num_rows($result) == 0)
		{
			print '<table>
			
			
			<tr>
			<th colspan="2" style="font-size:20px">
				Search Result
			</th>
			</tr>
			
			<tr>
				<td colspan="2">
					No search found
				</td>
			</tr>
			
			</table>';
		}
		else
		{
		//table 2
		print '<table>
	

		<tr>
			<th colspan="9" style="font-size:20px">
				Search Result
			</th>
		</tr>
		<tr>
			<th>Wine Name</th>
			<th>Wine Variety</th>
			<th>Year</th>
			<th>Winery</th>
			<th>Region</th>
			<th>Cost</th>
			<th>Availability</th>
			<th>No. Customer</th>
		</tr>';
		
		// Until there are no rows in the result set, fetch a row into
		// the $row array and ...
		while ($row =  mysqli_fetch_array($result))
		{
			// ... start a TABLE row ...
			echo "<tr>";
			
			// ... and print out each of the attributes in that row as 
			// a separate TD (Table Data).
			echo "<td>".($row["wine_name"])."</td>";
			echo "<td>".($row["variety"])."</td>";
			echo "<td>".($row["year"])."</td>";
			echo "<td>".($row["winery_name"])."</td>";
			echo "<td>".($row["region_name"])."</td>";
			echo "<td>".($row["cost"])."</td>";
			echo "<td>".($row["on_hand"])."</td>";
			echo "<td>".($row["TotalCustomer"])."</td>";
			
			// Finish the row
			echo "</tr>";
		}
			print "</table>";
		}
	}
?>

</body>
</html>

	
