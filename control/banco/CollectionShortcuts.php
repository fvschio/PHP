<?php
class CollectionShortcuts extends TPage
{
	public function __construct()
	{
		parent::__construct();

		try
		{
			TTransaction::open('curso');

			/* Exibe todos os objetos da classe Cliente.
			$clientes = Cliente::all();
			echo '<pre>'; print_r($clientes);
			*/
			
			/* Conta quantos clientes tem ativos e femininos dentro da classe Cliente
			$count = Cliente::where('situacao', '=', 'Y')
				 	->where('genero', '=', 'F')
					->count();
			print_r($count);
			 */
			/* Carrega/Exibe os clientes ativos e femininos dentro da classe Cliente
			$clientes = Cliente::where('situacao', '=', 'Y')
					   ->where('genero', '=', 'F')
				   	   ->load();
			echo '<pre>; print_r($clientes); echo '</pre>;
			 */
			/*Carrega/Exibe os clientes ativos e femininos dentro da classe Cliente em ordem por id	
			$clientes = Cliente::where('situacao', '=', 'Y')
					   ->where('genero', '=', 'F')
					   ->orderBy('id')
					   ->load();
			echo '<pre>; print_r($clientes); echo '</pre>;
			*/

			/* Carrega os objets paginadamente, neste caso do id 20 ao 30.
			$clientes = Cliente::where('id', '>', 0)
					   ->take(10)
					   ->skip(20)
					   ->load();
			echo '<pre>; print_r($clientes); echo '</pre>;
			*/

			/*Carrega/Exibe somente o primeiro registro que passa pela situação e genero.	
			$clientes = Cliente::where('situacao', '=', 'Y')
					   ->where('genero', '=', 'F')
					   ->firs();
			echo '<pre>; print_r($cliente); echo '</pre>;
			*/
			/*Atualiza o campo 3 do objeto cidade com os dados definidos no set.	
			Cliente::where('cidade_id', '=', '3')
				->set('telefone','22222-44444')
				->update();
			*/
			/* Deletar 
			Cliente::where('categoria_id', '=', '3')
			       ->delete();
			*/
			/* Retorna um array plano, coloca todos objetos em 1 vetor
			$clientes = CLiente::getIndexedArray('id','nome');
			echo '<pre>'; print_r($clientes); echo '</pre>';
			*/
			/*Retorna em um array plano, os clientes ativos em ordem
			$clientes = Cliente::where('situacao', '=', 'Y')
				 	   ->orderBy('id')
				   	   ->getIndexedArray('id', 'nome');
			echo '<pre>'; print_r($clientes); echo '</pre>';
			*/

			TTransaction::close();
		}

	}
	catch (Exception $e)
	{
			new TMessage('error', $e->getMessage());
	}
}
