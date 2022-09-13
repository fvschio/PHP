<?php
class ConexaoManual extends TPage
{
	public function __construct()
	{
		parent::__construct();

		try
		{
			TTransaction::open('curso');
			
			$conn = TTransaction::get();

			$result = $conn->query('SELECT id, nome FROM cliente ORDER BY id');

			foreach ($result as $row)
			{
				print $row['id'] . '-'.
				      $row['nome'] . "<br>\n";
			}
			TTransaction::close();
		}
		catch (Exception $e)
		{
			new TMessage('error', $e->getMessage());
		}
	}
}
