DELIMITER //
CREATE PROCEDURE `ProcessUserPurchasing4`(
	IN userid bigint
)

BEGIN

SELECT @sumtrxamt := SUM(transactionAmount)
 	FROM purchasehistory
	WHERE id_user = userid;
-- SELECT @sumtrxamt;

UPDATE user_master SET cashBalance=cashBalance-@sumtrxamt WHERE id_user = userid; 

UPDATE user_master SET cashBalance=cashBalance-@sumtrxamt WHERE id_user = userid; 

END//
DELIMITER ;

SELECT * FROM user_master
call ProcessUserPurchasing4(0)

SELECT * FROM user_master -- 700.700000 616.620000

SELECT * FROM restaurant_master -- 700.700000 616.620000

--
SELECT a.restaurantName,a.transactionAmount,b.cashBalance,b.cashBalance-a.transactionAmount 'balance'
FROM purchasehistory a
LEFT OUTER
JOIN restaurant_master b ON a.restaurantName=b.restaurantName
WHERE a.id_user=0

SELECT a.restaurantName,a.transactionAmount,b.cashBalance,b.cashBalance-a.transactionAmount 'balance'
FROM restaurant_master b
RIGHT OUTER JOIN purchasehistory a ON a.restaurantName=b.restaurantName
WHERE a.id_user=0

-- update

update restaurant_master b
RIGHT OUTER JOIN purchasehistory a ON a.restaurantName=b.restaurantName
set b.cashBalance=b.cashBalance-a.transactionAmount
WHERE a.id_user=0


