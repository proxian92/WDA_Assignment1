<!DOCTYPE HTML PUBLIC
                 "-//W3C//DTD HTML 4.01 Transitional//EN"
                 "http://www.w3.org/TR/html401/loose.dtd"/>
<html>
<head>
	<title>Search Result</title>
</head>
<body>
	
<?php
	set_include_path('C:/wamp/bin/php/php5.5.12/pear');
	require_once "HTML/Template/IT.php";
	
	require_once "DB.php";
	
	//Obtaining user input
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
		
		$template = new HTML_Template_IT(".");
		
		$template->loadTemplatefile("template2.tpl", true, true);

		$username = "root";
		$password = "";
		$hostname = "localhost";
		$dbname = "winestore";
		
		$db = "mysql://{$username}:{$password}@{$hostname}/{$dbname}";
		
		$connection =@ DB::connect($db);
		
		$result = @$connection->query($query);		
			
		if($yearstart > $yearend || $minimalprice > $maximalprice)
		{
			$template->setCurrentBlock("VALIDATION");
				
				$template->setVariable("ERRORMSG1", "I am sorry");
				$template->setVariable("ERRORMSG2", "Please ensure your minimum year or price is lesser than the maximum");
				
			$template->parseCurrentBlock();				
		}
		
		else if(($result->numRows())==0)
		{
			$template->setCurrentBlock("NOSEARCHRESULT");
			
				$template->setVariable("NOSEARCH", "No Result Found");
				
			$template->parseCurrentBlock();
		}
		
		else
		{			
			$template->setCurrentBlock("TABLEHEADER");
			
				$template->setVariable("H1", "Wine Name");
				$template->setVariable("H2", "Wine Variety");
				$template->setVariable("H3", "Year");
				$template->setVariable("H4", "Winery");
				$template->setVariable("H5", "Region");
				$template->setVariable("H6", "Cost");
				$template->setVariable("H7", "Availability");
				$template->setVariable("H8", "No. Customer");
				
			$template->parseCurrentBlock();
			
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC))
			{
				$template->setCurrentBlock("SEARCHRESULT");
				
					$template->setVariable("WINENAME", $row["wine_name"]);
					$template->setVariable("WINEVARIETY", $row["variety"]);
					$template->setVariable("WINEYEAR", $row["year"]);
					$template->setVariable("WINERYNAME", $row["winery_name"]);
					$template->setVariable("REGIONNAME", $row["region_name"]);
					$template->setVariable("PRICE", $row["cost"]);
					$template->setVariable("AVAILABILITY", $row["on_hand"]);
					$template->setVariable("ALLCUSTOMER", $row["TotalCustomer"]);
					
				$template->parseCurrentBlock();
			}
		}
			
		$template->show();
?>
</body>
</html>

	
