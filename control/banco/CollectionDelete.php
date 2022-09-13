<?php
class CollectionDelete extends TPage
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
		
			$valores = [];
			$valores['telefone'] = '1111-4444';

			$repository = new TRepository('Cliente');
			$repository->delete ( $criteria );
				
			TTransaction::close();
		}
		catch (Execption $e)
		{
			new TMessage('error', $e->getMessage());
		}
	}
}
