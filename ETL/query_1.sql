
	/*
	SELECT * FROM `keys`
		
	SELECT * FROM restaurant_master
	where restaurantname='Roma Ristorante'
	SELECT * FROM restaurant_menu
	
	SELECT * FROM user_master
	SELECT * FROM purchasehistory
	update purchasehistory set isprocessed=0 where id_user=0
	*/
	
	-- List top y restaurants that have more or less than x number of dishes within a price range
		
	SELECT a.restaurantname,COUNT(b.dishname) AS count from restaurant_master a
	INNER JOIN restaurant_menu b ON a.restaurant_id=b.restaurant_id
	WHERE b.price BETWEEN '13.88' AND '14'
	GROUP BY a.restaurantname
	HAVING COUNT >= 3
	
	
	SELECT * FROM restaurant_menu
	WHERE price BETWEEN '13.88' AND '14.00'
	
	SELECT a.restaurant_id from restaurant_master a
	INNER JOIN restaurant_menu b ON a.restaurant_id=b.restaurant_id
	GROUP BY a.restaurant_id
	HAVING COUNT(b.dishname) >= 14
	
	
	SELECT *  from restaurant_master a
	INNER JOIN restaurant_menu b ON a.restaurant_id=b.restaurant_id
	WHERE b.price BETWEEN '13.88' AND '14'
	GROUP BY a.restaurantname
	HAVING COUNT(b.dishname) >= 3
	
	-- List top y restaurants that have more or less than x number of dishes within a price range	
	SELECT * from restaurant_master a
	INNER JOIN restaurant_menu b ON a.restaurant_id=b.restaurant_id
	AND a.restaurant_id in
	(
		SELECT a.restaurant_id from restaurant_master a
		INNER JOIN restaurant_menu b ON a.restaurant_id=b.restaurant_id
		WHERE b.price BETWEEN '13.88' AND '14'
		GROUP BY a.restaurant_id
		HAVING COUNT(b.dishname) >= 3
		ORDER BY b.price
	)
	AND b.dishName in
	(
		SELECT b.dishName from restaurant_master a
		INNER JOIN restaurant_menu b ON a.restaurant_id=b.restaurant_id
		WHERE b.price BETWEEN '13.88' AND '14'
	)
	-- ORDER BY b.pricex	
	-- LIMIT 3
	
SELECT a.restaurantname from restaurant_master a
INNER JOIN restaurant_menu b ON a.restaurant_id=b.restaurant_id
WHERE b.price BETWEEN '13.88' AND '14'
GROUP BY a.restaurant_id
HAVING COUNT(b.dishname) >= 3
ORDER BY b.price
LIMIT 3


-- Search for restaurants or dishes by name, ranked by relevance to search term
SELECT a.restaurantName,b.dishName,b.price from restaurant_master a
INNER JOIN restaurant_menu b ON a.restaurant_id=b.restaurant_id
WHERE a.restaurantName LIKE '%ulu%' 
OR b.dishName LIKE '%ulu%'
ORDER BY 1,2
	
-- Process a user purchasing a dish from a restaurant, handling all relevant data changes in an atomic transaction


	