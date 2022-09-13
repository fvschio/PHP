<?php
class ObjetoAssociado extends TPage
{
	public function __construct()
	{
		parent::__construct();

		try
		{
			TTransaction::open('curso');
			
			//TTransaction::dump();

			$cliente = new Cliente(3);

			print $cliente->nome;
			echo '<br>';
			echo $cliente->cidade->nome;
			echo '<br>';
			echo $cliente->cidade->estado->nome;

			TTransaction::close();
		}
		catch (Exception $ei)
		{
			new TMessage('error', $e->getMessage());
		}
	}
}
