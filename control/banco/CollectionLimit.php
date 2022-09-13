<?php
class CollectionLimit extends TPage
{
	public function __construct()
	{
		parent::__construct();

		try
		{
			TTransaction::open('curso');
			
			$criteria = new TCriteria;
			$criteria->setProperty('limit', 10);
			$criteria->setProperty('offset', 20);
			$criteria->setProperty('order', 'id');

			//$criteria->add( new TFilter( 'situacao', '=', 'Y'));
			//$criteria->add( new TFilter( 'situacao', '=', 'Y'), TExpression::OR_OPERATOR );
			//$criteria->add( new TFilter( 'genero', '=', 'F'));
			//$criteria->add( new TFilter( 'genero', '=', 'F'), TExpression::OR_OPERATOR );

			$repository = new TRepository('Cliente');
			$objetos = $repository->load ( $criteria );
			
			if ($objetos)
			{
				foreach ($objetos as $objeto)
				{
					echo $objeto->id . '-' . $objeto->nome;
					echo '<br>';
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
