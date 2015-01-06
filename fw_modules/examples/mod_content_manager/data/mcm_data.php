<?php 
$data_mod_ctr=0;
for($max_ctr001=1;$data_mod_ctr<$max_ctr001;$data_mod_ctr++)
{
	include("data_mod$data_mod_ctr.php");
	if($max_ctr001>=1000)
	{
		die("MCM_DATA_ERR_CTR1:Vnútorná chyba načítavaného modulu! Počítadlo mimo bezpečný rozsah!");
	}
}
?>