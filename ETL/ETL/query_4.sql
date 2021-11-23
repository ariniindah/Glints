
DELIMITER //
CREATE PROCEDURE `ProcessUserPurchasing5`(
	IN userid bigint
)
BEGIN

/*calculate total purchase amount per user given*/
SELECT @sumtrxamt := SUM(transactionAmount)
 	FROM purchasehistory
	WHERE id_user = userid;

/*deduct cashBalance for user id given*/ 
UPDATE user_master SET cashBalance=cashBalance-@sumtrxamt WHERE id_user = userid; 

/*update flag isprocessed to all transaction from user id given */ 
UPDATE purchasehistory SET isprocessed=TRUE WHERE id_user = userid;

/*add restaurant's cash balance */
update restaurant_master b
RIGHT OUTER JOIN purchasehistory a ON a.restaurantName=b.restaurantName
set b.cashBalance=b.cashBalance-a.transactionAmount
WHERE a.id_user=userid;

END//
DELIMITER ;


call ProcessUserPurchasing5(3)
SELECT * FROM user_master WHERE id_user=3;
SELECT * FROM purchasehistory WHERE id_user=3;

SELECT a.restaurantName,a.transactionAmount,b.cashBalance,b.cashBalance-a.transactionAmount 'balance'
FROM restaurant_master b
RIGHT OUTER JOIN purchasehistory a ON a.restaurantName=b.restaurantName
WHERE a.id_user=3


SELECT * FROM restaurant_master
SELECT * FROM restaurant_menu
SELECT * FROM user_master
SELECT * FROM purchasehistory



