<?php
class ObjectLoad extends TPage
{
	public function __construct()
	{
		parent::__construct();

		try
		{
			TTransaction::open('curso');
			
			$produto = new Produto ( 8 );

			echo '<b>Descrição</b>' . $produto->descricao;
			echo '<br>'
			echo '<br>Estoque</br>' . $produto->estoque;

			TTransaction::close();
		}
		catch (Exception $e)
		{
			new TMessage('error', $e->getMessage());
		}
	}
}
