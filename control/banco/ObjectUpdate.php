<?php
class ObjectUpdate extends TPage
{
	public function __construct()
	{
		parent::__construct();

		try
		{
			TTransaction::open('curso');

			TTransaction::dump();

			$produto = Produto::find( 27 );
			
			if ($produto instanceof Produto)
			{
				$produto->descricao = 'Gravador CD-R';
				$produto->store();
			}
			else
			{
				echo 'Produto não encontrado';
			}

			TTransaction::close();
		}
		catch (Exception $e)
		{
			new TMessage('error', $e->getMessage());
		}
	}
}
