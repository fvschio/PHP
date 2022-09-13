<?php
class ObjectCreate extends TPage
{
	public function __construct()
	{
		parent::__construct();

		try
		{
			TTransaction::open('curso');
			
			/*	
			TTransaction::setLoggerFunction( function($mensagem) {
				print $mensagem . '<br>';
			});	
			*/

			TTranscation::dump();

			Produto::create( [
				'descricao' => 'CABO HDMI',
				'estoque' => 5,
				'preco_venda' => 20,
				'unidade' => 'PC'
			]);	

			new TMessage('info', 'Produto gravado com sucesso');

			TTransaction::close();
		}
		catch (Exception $e)
		{
			new TMessage('error', $e->getMessage());
		}
	}
}
