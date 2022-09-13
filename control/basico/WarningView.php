<?php
class WarningView extends TPage
{
	public function __construct()
	{
		parent::__construct();

		new TMessage('warning', 'Mensagem');
	}
}
