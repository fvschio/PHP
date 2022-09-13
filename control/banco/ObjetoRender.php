<?php
class ObjetoRender extends TPage
{
	public function __construct()
	{
		parent::__construct();

		try
		{
			TTransaction::open('curso');
			
			//TTransaction::dump();

			$produto = new Produto(3);
			
			//print_r($produto->toJson() );
			//print $produto->id . ' - ' . $produto->descricao;

			print $produto->render('0 produto (<b>{id}</b>) - nome (<b>{descricao}</b>) - preco R$ (<b>{preco_venda}</b>)');
			echo '<br>';

			//print $produto->preco_venda * $produto->estoque;
			echo 'Resultado: ';
			print $produto->evaluate('= {preco_venda} * {estoque} ');

			TTransaction::close();
		}
		catch (Exception $ei)
		{
			new TMessage('error', $e->getMessage());
		}
	}
}
