<?php

class InstituicoesForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'teste';
    private static $activeRecord = 'Instituicoes';
    private static $primaryKey = 'id';
    private static $formName = 'form_InstituicoesForm';

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
        $this->form->setFormTitle("Cadastro de Instituições");


        $razaosocial = new TEntry('razaosocial');
        $cep = new TEntry('cep');
        $logradouro = new TEntry('logradouro');
        $numero = new TEntry('numero');
        $bairro = new TEntry('bairro');
        $cidade = new TEntry('cidade');
        $estado = new TEntry('estado');

        $razaosocial->addValidation("Razão Social deve ser preenchido!", new TRequiredValidator()); 

        $cep->setMask('00000-000', true);
        $estado->setMaxLength(2);
        $bairro->setMaxLength(50);
        $cidade->setMaxLength(50);
        $logradouro->setMaxLength(50);
        $razaosocial->setMaxLength(50);

        $cep->setSize('100%');
        $numero->setSize('96%');
        $bairro->setSize('100%');
        $cidade->setSize('100%');
        $estado->setSize('100%');
        $logradouro->setSize('100%');
        $razaosocial->setSize('100%');

        $cep->placeholder = "Digite o CEP";
        $bairro->placeholder = "Digite o nome do bairro";
        $cidade->placeholder = "Digite o nome da cidade";
        $numero->placeholder = "Digite o Nº da casa, rua, ap";
        $razaosocial->placeholder = "Digite o nome da Instituição";
        $estado->placeholder = "Digite a sigla do Estado (Ex: RS, PR, MT)";
        $logradouro->placeholder = "Digite o logradouro (Ex: Rua, Avenida, BR101...)";

        $row1 = $this->form->addFields([new TLabel("Razão Social:", null, '14px', null)],[$razaosocial]);
        $row2 = $this->form->addFields([new TLabel("CEP:", null, '14px', null)],[$cep]);
        $row3 = $this->form->addFields([new TLabel("Logradouro:", null, '14px', null)],[$logradouro],[new TLabel("Nº:", null, '14px', null)],[$numero]);
        $row4 = $this->form->addFields([new TLabel("Bairro:", null, '14px', null)],[$bairro]);
        $row5 = $this->form->addFields([new TLabel("Cidade:", null, '14px', null)],[$cidade],[new TLabel("UF:", null, '14px', null)],[$estado]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['InstituicoesHeaderList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Registros","Cadastro de Instituições"]));
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

            $object = new Instituicoes(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('InstituicoesHeaderList', 'onShow', $loadPageParam); 

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

                $object = new Instituicoes($key); // instantiates the Active Record 

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

