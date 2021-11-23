SET @user := 0;
SELECT @group := id_user FROM purchasehistory WHERE id_user = @user;
SELECT @GROUP

SET @user := 0;
SELECT @sumtrxamt := SUM(transactionAmount)
 	FROM purchasehistory
	WHERE id_user = @user;
SELECT @sumtrxamt