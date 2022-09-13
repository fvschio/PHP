<?php
class InformationView extends TPage
{
	public function __construct()
	{
		parent::__construct();

		new TMessage('info', 'Mensagem');
	}
}
