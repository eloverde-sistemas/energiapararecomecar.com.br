<?	

$cupomNum = 1;

for($i=0; $i<=200000; $i++){
	
	$cupomNum ++;
	
	$valorCompra = rand(1, 900).".".rand(0,99);
	
	inserirDados("evento_cupom", array('id_evento'=>1, 'id_loja'=>rand(2,4), 'cupom'=>$cupomNum, 'compra_dt'=>subDayIntoDate(date('Y-m-d'), (30-rand(1,30)) ), 'compra_valor'=>"$valorCompra"));
	
}

?>