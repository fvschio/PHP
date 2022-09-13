<?php
class ErrorView extends TPage
{
	public function __construct()
	{
		parent::__construct();

		new TMessage('error', 'Mensagem');
	}
}
