<?php
// Get the contents of the JSON file 
$strJsonFileContents = file_get_contents("restaurant_with_menu.json");

$data = json_decode($strJsonFileContents, true);

// open mysql connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "glints";
$table1 = 'restaurant_master';
$table2 = 'restaurant_menu';
$con = mysqli_connect($host, $username, $password, $dbname) or die('Error in Connecting: ' . mysqli_error($con));

// //truncate table before process
mysqli_query($con, "truncate table $table1");
mysqli_query($con, "truncate table $table2");

// loop through the array
foreach ($data as $row) {
    $restaurantName = $row['restaurantName'];
    $cashBalance = $row['cashBalance'];
    $openingHours = $row['openingHours'];
    $menus = $row['menu'];

    $st = mysqli_prepare($con, 'INSERT INTO restaurant_master(restaurantName,cashBalance, openingHours) VALUES (?, ?, ?)');

    // bind variables to insert query params
    mysqli_stmt_bind_param($st, 'sss', $restaurantName, $cashBalance, $openingHours);

    // execute insert query
    mysqli_stmt_execute($st);

    //get auto increment id from last successfull insert
    $last_id = mysqli_insert_id($con);

    //if ($restaurantName == '024 Grille') {
    foreach ($menus as $menu) {
        $dishName = $menu['dishName'];
        $price = $menu['price'];

        $st2 = mysqli_prepare($con, 'INSERT INTO restaurant_menu(restaurant_id,dishName, price) VALUES (?, ?, ?)');

        // bind variables to insert query params
        mysqli_stmt_bind_param($st2, 'iss', $last_id, $dishName, $price);

        // execute insert query
        mysqli_stmt_execute($st2);
    }
    //}
}

echo $last_id . " restaurants data successfully inserted!<br>";

/////////////////////////////////////////////////////////////////////

$strJsonFileContents = file_get_contents("users_with_purchase_history.json");
$data = json_decode($strJsonFileContents, true);
$table3 = 'purchasehistory';
$table4 = 'user_master';
mysqli_query($con, "truncate table $table3");
mysqli_query($con, "truncate table $table4");

// loop through the array
foreach ($data as $row) {
    $id_user = $row['id'];
    $name = $row['name'];
    $cashBalance = $row['cashBalance'];
    $purchaseHistorys = $row['purchaseHistory'];

    $st = mysqli_prepare($con, 'INSERT INTO user_master(id_user,name, cashBalance) VALUES (?, ?, ?)');

    // bind variables to insert query params
    mysqli_stmt_bind_param($st, 'iss', $id_user, $name, $cashBalance);

    // execute insert query
    mysqli_stmt_execute($st);

    //if ($restaurantName == '024 Grille') {
    foreach ($purchaseHistorys as $purchaseHistory) {
        $dishName = $purchaseHistory['dishName'];
        $restaurantName = $purchaseHistory['restaurantName'];
        $transactionAmount = $purchaseHistory['transactionAmount'];
        $transactionDate = $purchaseHistory['transactionDate'];

        $st2 = mysqli_prepare($con, 'INSERT INTO purchasehistory(id_user,dishName, restaurantName, transactionAmount, transactionDate) VALUES (?, ?, ?, ?, ?)');

        // bind variables to insert query params
        mysqli_stmt_bind_param($st2, 'issss', $id_user, $dishName, $restaurantName, $transactionAmount, $transactionDate);

        // execute insert query
        mysqli_stmt_execute($st2);
    }
    //}
}

echo "users data successfully inserted!";