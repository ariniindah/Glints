<?php

$table = "user_aktif"; //ini name table




$method = "getData";
$aksi = "getToken";
$username =  "ecc";
$password = "ecc@wpkr";
$token = GetToken($aksi, $username, $password);

$startpage = 0;
$CountRows = GetMaxPage($token, $method, $table, $startpage);
$MaxPage = floor($CountRows / 100);

echo "Token : " . $token . "<br>";
echo "Table : " . $table . "<br>";
echo "Rows Count : " . $CountRows . "<br>";
echo "Max Page : " . $MaxPage . "<br>";

function GetToken($aksi, $username, $password)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://ecocare.webpakar.net/wpkr/home.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
                "aksi" : "' . $aksi . '"
                , "username" : "' . $username . '"
                , "password" : "' . $password . '"
            }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: text/plain'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $rs = json_decode($response, true);
    //echo $rs["data"]["token"];
    return $rs["data"]["token"];
}

function GetMaxPage($token, $method, $table, $startpage)
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://ecocare.webpakar.net/wpkr/home.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "token" : "' . $token . '"
            , "aksi" : "' . $method . '"
            , "table" : "' . $table . '"
            , "page" : "' . $startpage . '"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: text/plain'
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $rs = json_decode($response, true);
    //echo $rs["data"]["token"];
    return $rs["data"]["total"];
}

for ($page = 0; $page <= $MaxPage; $page++) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://ecocare.webpakar.net/wpkr/home.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "token" : "' . $token . '"
            , "aksi" : "' . $method . '"
            , "table" : "' . $table . '"
            , "page" : "' . $page . '"
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: text/plain'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    //echo $response;

    // open mysql connection
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sales_app";
    $con = mysqli_connect($host, $username, $password, $dbname) or die('Error in Connecting: ' . mysqli_error($con));

    //truncate table only for the first loop
    if ($page == 0) {
        mysqli_query($con, "truncate table $table");
    }

    // use prepare statement for insert query
    if ($table == "actual") {

        $st = mysqli_prepare($con, 'INSERT INTO actual(id,user, plan, customer, type_visit, nama, datetime, confirmasi_visit, confirmasi_visit_time, survey, checkin, checkin_long, checkin_lat,checkout, checkout_result, checkout_long, checkout_lat
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        // bind variables to insert query params
        mysqli_stmt_bind_param($st, 'iiiiissisisssssss', $id, $user, $plan, $customer, $type_visit, $nama, $datetime, $confirmasi_visit, $confirmasi_visit_time, $survey, $checkin, $checkin_long, $checkin_lat, $checkout, $checkout_result, $checkout_long, $checkout_lat);

        $data = json_decode($response, true);

        // loop through the array
        foreach ($data['data']['actual'] as $row) {
            $id = $row['id'];
            $user = $row['user'];
            $plan = $row['plan'];
            $customer = $row['customer'];
            $type_visit = $row['type_visit'];
            $nama = $row['nama'];
            $datetime = $row['datetime'];
            $confirmasi_visit = $row['confirmasi_visit'];
            $confirmasi_visit_time = $row['confirmasi_visit_time'];
            $survey = $row['survey'];
            $checkin = $row['checkin'];
            $checkin_long = $row['checkin_long'];
            $checkin_lat = $row['checkin_lat'];
            $checkout = $row['checkout'];
            $checkout_result = $row['checkout_result'];
            $checkout_long = $row['checkout_long'];
            $checkout_lat = $row['checkout_lat'];

            // execute insert query
            mysqli_stmt_execute($st);
        }
    } else if ($table == "customer") {
        $st = mysqli_prepare($con, 'INSERT INTO customer(id, user, nomor_kontrak, nama, cp, jabatan, cp2, jabatan2, alamat, hp, telp, email, npwp, npwp_nama, npwp_alamat, foto, type_customer, segment, tanggal, tanggal_to) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iissssssssssssssiiss', $id, $user, $nomor_kontrak, $nama, $cp, $jabatan, $cp2, $jabatan2, $alamat, $hp, $telp, $email, $npwp, $npwp_nama, $npwp_alamat, $foto, $type_customer, $segment, $tanggal, $tanggal_to);

        $data = json_decode($response, true);

        foreach ($data['data']['customer'] as $row) {
            $id = $row['id'];
            $user = $row['user'];
            $nomor_kontrak = $row['nomor_kontrak'];
            $nama = $row['nama'];
            $cp = $row['cp'];
            $jabatan = $row['jabatan'];
            $cp2 = $row['cp2'];
            $jabatan2 = $row['jabatan2'];
            $alamat = $row['alamat'];
            $hp = $row['hp'];
            $telp = $row['telp'];
            $email = $row['email'];
            $npwp = $row['npwp'];
            $npwp_nama = $row['npwp_nama'];
            $npwp_alamat = $row['npwp_alamat'];
            $foto = $row['foto'];
            $type_customer = $row['type_customer'];
            $segment = $row['segment'];
            $tanggal = $row['tanggal'];
            $tanggal_to = $row['tanggal_to'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "segment") {
        $st = mysqli_prepare($con, 'INSERT INTO segment(id, nama) VALUES (?, ?)');

        mysqli_stmt_bind_param($st, 'is', $id, $nama);

        $data = json_decode($response, true);

        foreach ($data['data']['segment'] as $row) {
            $id = $row['id'];
            $nama = $row['nama'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "user") {
        $st = mysqli_prepare($con, 'INSERT INTO user(id, posisi, admin, nama, level, aktif, no_wa) VALUES (?, ?, ?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iiisiis', $id, $posisi, $admin, $nama, $level, $aktif, $no_wa);

        $data = json_decode($response, true);

        foreach ($data['data']['user'] as $row) {
            $id = $row['id'];
            $posisi = $row['posisi'];
            $admin = $row['admin'];
            $nama = $row['nama'];
            $level = $row['level'];
            $aktif = $row['aktif'];
            $no_wa = $row['no_wa'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "plan") {
        $st = mysqli_prepare($con, 'INSERT INTO plan(id, user, customer, type_visit, nama, datetime, confirmasi_visit, confirmasi_visit_time, survey) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iiiissisi', $id, $user, $customer, $type_visit, $nama, $datetime, $confirmasi_visit, $confirmasi_visit_time, $survey);

        $data = json_decode($response, true);

        foreach ($data['data']['plan'] as $row) {
            $id = $row['id'];
            $user = $row['user'];
            $customer = $row['customer'];
            $type_visit = $row['type_visit'];
            $nama = $row['nama'];
            $datetime = $row['datetime'];
            $confirmasi_visit = $row['confirmasi_visit'];
            $confirmasi_visit_time = $row['confirmasi_visit_time'];
            $survey = $row['survey'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "survey") {
        $st = mysqli_prepare($con, 'INSERT INTO survey(id, user, customer, note, tanggal) VALUES (?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iiiss', $id, $user, $customer, $note, $tanggal);

        $data = json_decode($response, true);

        foreach ($data['data']['survey'] as $row) {
            $id = $row['id'];
            $user = $row['user'];
            $customer = $row['customer'];
            $note = $row['note'];
            $tanggal = $row['tanggal'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "survey_detail") {
        $st = mysqli_prepare($con, 'INSERT INTO survey_detail(id, survey, forecast_data, unit, value) VALUES (?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iiiii', $id, $survey, $forecast_data, $unit, $value);

        $data = json_decode($response, true);

        foreach ($data['data']['survey_detail'] as $row) {
            $id = $row['id'];
            $survey = $row['survey'];
            $forecast_data = $row['forecast_data'];
            $unit = $row['unit'];
            $value = $row['value'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "absen") {
        $st = mysqli_prepare($con, 'INSERT INTO `absen`(`id`, `user_id`, `tanggal`, `tipe`, `long`, `lat`, `auto_insert`) VALUES (?, ?, ?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iisissi', $id, $user_id, $tanggal, $tipe, $long, $lat, $auto_insert);

        $data = json_decode($response, true);

        foreach ($data['data']['absen'] as $row) {
            $id = $row['id'];
            $user_id = $row['user_id'];
            $tanggal = $row['tanggal'];
            $tipe = $row['tipe'];
            $long = $row['long'];
            $lat = $row['lat'];
            $auto_insert = $row['auto_insert'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "user_target") {
        $st = mysqli_prepare($con, 'INSERT INTO user_target(`id`, `user`, `tanggal`, `tanggal_akhir`, `target_new`, `target_exc`, `target_renewal`, `note`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iissiiis', $id, $user, $tanggal, $tanggal_akhir, $target_new, $target_exc, $target_renewal, $note);

        $data = json_decode($response, true);

        foreach ($data['data']['user_target'] as $row) {
            $id = $row['id'];
            $user = $row['user'];
            $tanggal = $row['tanggal'];
            $tanggal_akhir = $row['tanggal_akhir'];
            $target_new = $row['target_new'];
            $target_exc = $row['target_exc'];
            $target_renewal = $row['target_renewal'];
            $note = $row['note'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "user_posisi") {
        $st = mysqli_prepare($con, 'INSERT INTO user_posisi(`id`, `nama`) VALUES (?, ?)');

        mysqli_stmt_bind_param($st, 'is', $id, $nama);

        $data = json_decode($response, true);

        foreach ($data['data']['user_posisi'] as $row) {
            $id = $row['id'];
            $tipe = $row['nama'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "user_level") {
        $st = mysqli_prepare($con, 'INSERT INTO user_level(`id`, `tipe`) VALUES (?, ?)');

        mysqli_stmt_bind_param($st, 'is', $id, $tipe);

        $data = json_decode($response, true);

        foreach ($data['data']['user_level'] as $row) {
            $id = $row['id'];
            $tipe = $row['tipe'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "user_aktif") {
        $st = mysqli_prepare($con, 'INSERT INTO user_aktif(`id`, `tipe`) VALUES (?, ?)');

        mysqli_stmt_bind_param($st, 'is', $id, $tipe);

        $data = json_decode($response, true);

        foreach ($data['data']['user_aktif'] as $row) {
            $id = $row['id'];
            $tipe = $row['tipe'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "type_visit") {
        $st = mysqli_prepare($con, 'INSERT INTO type_visit(`id`, `nama`) VALUES (?, ?)');

        mysqli_stmt_bind_param($st, 'is', $id, $nama);

        $data = json_decode($response, true);

        foreach ($data['data']['type_visit'] as $row) {
            $id = $row['id'];
            $nama = $row['nama'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "status") {
        $st = mysqli_prepare($con, 'INSERT INTO status(`id`, `nama`) VALUES (?, ?)');

        mysqli_stmt_bind_param($st, 'is', $id, $nama);

        $data = json_decode($response, true);

        foreach ($data['data']['status'] as $row) {
            $id = $row['id'];
            $nama = $row['nama'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "sales_detail") {
        $st = mysqli_prepare($con, 'INSERT INTO sales_detail(`id`, `sales`, `forecast_data`, `unit`, `value`) VALUES (?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iiiii', $id, $sales, $forecast_data, $unit, $value);

        $data = json_decode($response, true);

        foreach ($data['data']['sales_detail'] as $row) {
            $id = $row['id'];
            $sales = $row['sales'];
            $forecast_data = $row['forecast_data'];
            $unit = $row['unit'];
            $value = $row['value'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "sales") {
        $st = mysqli_prepare($con, 'INSERT INTO sales(id, user, forecast, status, coc_sign, cancel_reason, pending_reason, tanggal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iiiissss', $id, $user, $forecast, $status, $coc_sign, $cancel_reason, $pending_reason, $tanggal);

        $data = json_decode($response, true);

        foreach ($data['data']['sales'] as $row) {
            $id = $row['id'];
            $user = $row['user'];
            $forecast = $row['forecast'];
            $status = $row['status'];
            $coc_sign = $row['coc_sign'];
            $cancel_reason = $row['cancel_reason'];
            $pending_reason = $row['pending_reason'];
            $tanggal = $row['tanggal'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "quot_detail") {
        $st = mysqli_prepare($con, 'INSERT INTO quot_detail(`id`, `quot`, `forecast_data`, `unit`, `value`) VALUES (?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iiiii', $id, $quot, $forecast_data, $unit, $value);

        $data = json_decode($response, true);

        foreach ($data['data']['quot_detail'] as $row) {
            $id = $row['id'];
            $quot = $row['quot'];
            $forecast_data = $row['forecast_data'];
            $unit = $row['unit'];
            $value = $row['value'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "quot") {
        $st = mysqli_prepare($con, 'INSERT INTO quot(`id`, `user`, `customer`, `tanggal`) VALUES (?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iiis', $id, $user, $customer, $tanggal);

        $data = json_decode($response, true);

        foreach ($data['data']['quot'] as $row) {
            $id = $row['id'];
            $user = $row['user'];
            $customer = $row['customer'];
            $tanggal = $row['tanggal'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "forecast_detail") {
        $st = mysqli_prepare($con, 'INSERT INTO forecast_detail(`id`, `forecast`, `forecast_data`, `unit`, `value`) VALUES (?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iiiii', $id, $forecast, $forecast_data, $unit, $value);

        $data = json_decode($response, true);

        foreach ($data['data']['forecast_detail'] as $row) {
            $id = $row['id'];
            $forecast = $row['forecast'];
            $forecast_data = $row['forecast_data'];
            $unit = $row['unit'];
            $value = $row['value'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "forecast_data") {
        $st = mysqli_prepare($con, 'INSERT INTO forecast_data(`id`, `nama`, `keterangan`) VALUES (?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iss', $id, $nama, $keterangan);

        $data = json_decode($response, true);

        foreach ($data['data']['forecast_data'] as $row) {
            $id = $row['id'];
            $nama = $row['nama'];
            $keterangan = $row['keterangan'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "forecast") {
        $st = mysqli_prepare($con, 'INSERT INTO forecast(`id`, `user`, `customer`, `tanggal`, `tanggal_to`) VALUES (?, ?, ?, ?, ?)');

        mysqli_stmt_bind_param($st, 'iiiss', $id, $user, $customer, $tanggal, $tanggal_to);

        $data = json_decode($response, true);

        foreach ($data['data']['forecast'] as $row) {
            $id = $row['id'];
            $user = $row['user'];
            $customer = $row['customer'];
            $tanggal = $row['tanggal'];
            $tanggal_to = $row['tanggal_to'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "auto_insert") {
        $st = mysqli_prepare($con, 'INSERT INTO auto_insert(`id`, `tipe`) VALUES (?, ?)');

        mysqli_stmt_bind_param($st, 'is', $id, $tipe);

        $data = json_decode($response, true);

        foreach ($data['data']['auto_insert'] as $row) {
            $id = $row['id'];
            $tipe = $row['tipe'];

            mysqli_stmt_execute($st);
        }
    } else if ($table == "absen_tipe") {
        $st = mysqli_prepare($con, 'INSERT INTO absen_tipe(`id`, `tipe`) VALUES (?, ?)');

        mysqli_stmt_bind_param($st, 'is', $id, $tipe);

        $data = json_decode($response, true);

        foreach ($data['data']['absen_tipe'] as $row) {
            $id = $row['id'];
            $tipe = $row['tipe'];

            mysqli_stmt_execute($st);
        }
    }

    // mysqli_close($con);
}

mysqli_close($con);
echo "Data successfully stored!";
