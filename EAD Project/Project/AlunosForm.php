<?php

class AlunosForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'teste';
    private static $activeRecord = 'Alunos';
    private static $primaryKey = 'matricula';
    private static $formName = 'form_AlunosForm';

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de Alunos");


        $nome = new TEntry('nome');
        $cpf = new TEntry('cpf');
        $genero = new TEntry('genero');
        $graduacao = new TDBCombo('graduacao', 'teste', 'Graduacao', 'nome', '{nome}','id asc'  );
        $Idade = new TEntry('Idade');
        $telefone = new TEntry('telefone');
        $cep = new TEntry('cep');
        $logradouro = new TEntry('logradouro');
        $numero = new TEntry('numero');
        $bairro = new TEntry('bairro');
        $cidade = new TEntry('cidade');
        $estado = new TEntry('estado');

        $nome->addValidation("O campo Nome do Aluno deve ser preenchido, ", new TRequiredValidator()); 
        $cpf->addValidation("Atenção!", new TCPFValidator(), []); 

        $graduacao->enableSearch();
        $cep->setMask('00000-000', true);
        $Idade->setMask('00/00/0000', true);
        $cpf->setMask('000.000.000-00', true);
        $telefone->setMask('(00) 00000-0000', true);

        $nome->setMaxLength(100);
        $estado->setMaxLength(2);
        $bairro->setMaxLength(50);
        $cidade->setMaxLength(50);
        $logradouro->setMaxLength(50);

        $cep->setSize('100%');
        $cpf->setSize('100%');
        $nome->setSize('100%');
        $Idade->setSize('100%');
        $genero->setSize('100%');
        $numero->setSize('100%');
        $bairro->setSize('100%');
        $cidade->setSize('100%');
        $estado->setSize('100%');
        $telefone->setSize('100%');
        $graduacao->setSize('100%');
        $logradouro->setSize('100%');

        $cpf->placeholder = "Digite o CPF";
        $cep->placeholder = "Digite o CEP";
        $telefone->placeholder = "Digite o telefone";
        $nome->placeholder = "Digite o nome do Aluno";
        $bairro->placeholder = "Digite o nome do bairro";
        $cidade->placeholder = "Digite o nome da cidade";
        $Idade->placeholder = "Digite a data de nascimento";
        $genero->placeholder = "Digite o Gênero (Ex: F ou M)";
        $numero->placeholder = "Digite o Número da casa, rua, ap...";
        $logradouro->placeholder = "Digite o nome da rua, avenida, BR...";
        $estado->placeholder = "Digite a sigla do estado (Ex: RS, PR, MT)";

        $row1 = $this->form->addFields([new TLabel("Nome do Aluno:", null, '14px', null)],[$nome]);
        $row2 = $this->form->addFields([new TLabel("CPF:", null, '14px', null)],[$cpf]);
        $row3 = $this->form->addFields([new TLabel("Gênero:", null, '14px', null)],[$genero],[new TLabel("Graduação:", null, '14px', null)],[$graduacao]);
        $row4 = $this->form->addFields([new TLabel("Data de Nascimento:", null, '14px', null)],[$Idade],[new TLabel("Telefone:", null, '14px', null)],[$telefone]);
        $row5 = $this->form->addFields([new TLabel("CEP:", null, '14px', null)],[$cep]);
        $row6 = $this->form->addFields([new TLabel("Logradouro:", null, '14px', null)],[$logradouro],[new TLabel("Nº:", null, '14px', null)],[$numero]);
        $row7 = $this->form->addFields([new TLabel("Bairro:", null, '14px', null)],[$bairro]);
        $row8 = $this->form->addFields([new TLabel("Cidade:", null, '14px', null)],[$cidade],[new TLabel("UF:", null, '14px', null)],[$estado]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['AlunosHeaderList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Registros","Cadastro de Alunos"]));
        }
        $container->add($this->form);

        parent::add($container);

    }

    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Alunos(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            // get the generated {PRIMARY_KEY}
            $data->matricula = $object->matricula; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('AlunosHeaderList', 'onShow', $loadPageParam); 

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Alunos($key); // instantiates the Active Record 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

    }

    public function onShow($param = null)
    {

    } 

}

