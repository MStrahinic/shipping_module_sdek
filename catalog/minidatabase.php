<?php
$mysqli = new mysqli("localhost", "xxx", "zzz", "yyy");
$mysqli->query("set names 'utf8'");

$upit = "
CREATE TABLE IF NOT EXISTS city
(
city_id int PRIMARY KEY,
city_name_obl_name varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
city_name varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
city_obl_name varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
city_center int NOT NULL,
city_post_code varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
city_nal_sum_limit float
);";

$mysqli->query($upit);

$result = $mysqli->query("SELECT * FROM city");
$row_cnt = $result->num_rows;

if($row_cnt === 0){
	
$upit = "INSERT INTO city VALUES ";

$row = 0;
if (($handle = fopen("/home/srv42026/htdocs/city.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		$row++;
		if($row == 1)
			continue;

		$id = str_replace(',', '', $data[0]);
		$name = $data[1];
		$cityname = $data[2];
		$oblname = $data[3];
		$center = 0;
		$postcode = $data[5];
		$nalsumlimit = 'NULL';

		if($data[4] == '*')
			$center = 1;

		if($data[6] != 'без ограничений')
			$nalsumlimit = $data[6];


		$upit .= "($id, '$name', '$cityname', '$oblname', $center, '$postcode', $nalsumlimit),";

	}
    $upit = substr_replace($upit ,"",-1);
    $mysqli->query($upit);
    fclose($handle);
}
}

?>