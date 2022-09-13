<?php
class FormularioBootstrapEstatico extends TPage
{
	private $form;

	public function __construct()
	{
		parent::__construct();

		$this->form = new BootstrapFormBuilder;
		$this->form->setFormTitle('Formulário bootstrap estático');

		$id           = new TEntry('id');
		$descricao    = new TEntry('descricao');
		$senha        = new TPassword('senha');


		$this->form->addFields( [ new TLabel('Id') ], [$id] );
		$this->form->addFields( [ new TLabel('Descrição') ], [$descricao] );
		$this->form->addFields( [ new TLabel('Senha') ], [$senha] );

		$this->form->addAction( ' Enviar', new TAction( [$this, 'onSend']), 'fa:save');
		
		parent::add($this->form);
	}

	public static function onSend($param)
	{
		new TMessage('info', str_replace(',', '<br>', json_encode($param)));
	}
}

