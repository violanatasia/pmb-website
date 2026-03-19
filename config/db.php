<?php

$host = "localhost"; //nama host nya
$username = "root"; //nama username mysql nya default nya dah begini
$password = ""; //password sql default
$database = "website_pmb"; //nama database nya

$conn = mysqli_connect($host, $username, $password, $database);

//ini untuk cek klo koneksi nya gagal atau ga terhubung dia bakal keluarin output koneksi gagal
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");


?>