<?php
class CollectionCount extends TPage
{
	public function __construct()
	{
		parent::__construct();

		try
		{
			TTransaction::open('curso');
			
			$criteria = new TCriteria;
			$criteria->add( new TFilter( 'situacao', '=', 'Y'));
			//$criteria->add( new TFilter( 'situacao', '=', 'Y'), TExpression::OR_OPERATOR );
			$criteria->add( new TFilter( 'genero', '=', 'F'));
			//$criteria->add( new TFilter( 'genero', '=', 'F'), TExpression::OR_OPERATOR );

			$repository = new TRepository('Cliente');
			$count = $repository->count ( $criteria );

			new TMessage('info', "Registros: $count");


			TTransaction::close();
		}
		catch (Execption $e)
		{
			new TMessage('error', $e->getMessage());
		}
	}
}
