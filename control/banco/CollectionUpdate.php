<?php
class CollectionUpdate extends TPage
{
	public function __construct()
	{
		parent::__construct();

		try
		{
			TTransaction::open('curso');
			TTransaction::dump();

			$criteria = new TCriteria;
			$criteria->add( new TFilter( 'situacao', '=', 'Y'));
			//$criteria->add( new TFilter( 'situacao', '=', 'Y'), TExpression::OR_OPERATOR );
			$criteria->add( new TFilter( 'genero', '=', 'F'));
			//$criteria->add( new TFilter( 'genero', '=', 'F'), TExpression::OR_OPERATOR );

			$repository = new TRepository('Cliente');
			$objetos = $repository->load ( $criteria );
			
			if ($objetos)
			{
				foreach ($objetos as $objeto)
				{
					$objeto->telefone = '222 333 433';
					$objeto->store();
				}
			}

			TTransaction::close();
		}
		catch (Execption $e)
		{
			new TMessage('error', $e->getMessage());
		}
	}
}