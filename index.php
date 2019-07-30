

<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "regiao";

$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
	echo "deu errado";
}else{
	// echo "deu certo";
}



$url = "json.json";

$url = file_get_contents($url);

$json = json_decode($url);




// foreach ($json as $key => $value) {
// 	$query = "insert into regiao (regiao, cod_u, uf, cod_m, municipio)
// 		  values('$value->macro_regiao', '$value->cod_uf', '$value->uf', '$value->cod_municipio', '$value->municipio')";

// 	$query = mysqli_query($con, $query);
// }

$query = "select regiao from regiao group by regiao";
$query = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($query)) {
	$regioes [] = $row;
}


$query = "select uf from regiao group by uf";
$query = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($query)) {
	$uf [] = $row;
}


$query = "select municipio from regiao group by uf";
$query = mysqli_query($con, $query);

while ($row = mysqli_fetch_assoc($query)) {
	$municipio [] = $row;
}




 // var_dump($regioes);
 // var_dump($uf);
$code = "";
$i = 0;
$count_uf = 0;
$count_mu = 0;
foreach ($regioes as $key => $reg_r) { ?>
<node id="R<?php echo $key;?>" label="<?php echo $reg_r['regiao'];?>">


	<?php

	$query1 = "select * from regiao where regiao = '".$reg_r['regiao']."' group by uf";
	$query1 = mysqli_query($con, $query1);

	?>
	<?php while ($row_uf = mysqli_fetch_assoc($query1)) {?>
		
		<?php

		$query2 = "select * from regiao where regiao = '".$reg_r['regiao']."' and uf = '".$row_uf['uf']."' group by municipio ";
	    $query2 = mysqli_query($con, $query2);
	    // $count_uf +1;
		?>
<isComposedBy>
			<node id="R<?php echo $key.$count_uf;?>" label="<?php echo $row_uf['cod_u'];?>:<?php echo $row_uf['uf'];?>">
				<isComposedBy>

					<?php while ($row_mu = mysqli_fetch_assoc($query2)) {?>
<node id="RM<?php echo $key.$count_uf.$count_mu;?>" label="<?php echo $row_mu['cod_m'].":".$row_mu['municipio'];?>" />
					<?php $count_mu++;}?>

				</isComposedBy>
			</node>

		</isComposedBy>
	<?php $count_uf++;} ?>
</node>


<?php }?>