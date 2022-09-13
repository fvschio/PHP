<?php
class FormularioBootstrap extends TPage
{
	private $form;

	public function __construct()
	{
		parent::__construct();

		$this->form = new BootstrapFormBuilder;
		$this->form->setFormTitle('Formulário bootstrap');

		$id           = new TEntry('id');
		$descricao    = new TEntry('descricao');
		$senha        = new TPassword('senha');
		$dt_criacao   = new TDateTime('dt_criacao');
		$dt_expiracao = new TDate('dt_expiracao');
		$valor        = new TEntry('valor');
		$cor          = new TColor('cor');
		$peso         = new TSpinner('peso');
		$tipo         = new TCombo('tipo');
		$texto        = new TText('texto');

		$id->setEditable(FALSE);
		$cor->setSize('100%');
		$dt_criacao->setMask('dd/mm/yyyy hh:ii');
		$dt_criacao->setDatabaseMask('yyy-mm-dd hh:ii');

		$this->form->addFields( [ new TLabel('Id') ], [$id] );
		$this->form->addFields( [ new TLabel('Descrição') ], [$descricao] );
		$this->form->addFields( [ new TLabel('Senha') ], [$senha] );
		$this->form->addFields( [ new TLabel('Dt. Criação') ], [$dt_criacao], [new TLabel('Dt. expiração')], [$dt_expiracao] );
		$this->form->addFields( [ new TLabel('Valor') ], [$valor], [new TLabel('Cor')], [$cor] );
		$this->form->addFields( [ new TLabel('Peso') ], [$peso], [new TLabel('Tipo')], [$tipo] );

		$this->form->addAction( ' Enviar', new TAction( [$this, 'onSend']), 'fa:save');
		
		parent::add($this->form);
	}

	public function onSend($param)
	{
		$data = $this->form->getData();
		$this->form->setData($data);

		new TMessage('info', str_replace(',', '<br>', json_encode($data)));
	}
}

