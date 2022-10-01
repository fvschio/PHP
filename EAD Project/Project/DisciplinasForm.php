<?php

class DisciplinasForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'teste';
    private static $activeRecord = 'Disciplinas';
    private static $primaryKey = 'id';
    private static $formName = 'form_DisciplinasForm';

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
        $this->form->setFormTitle("Cadastro de Disciplinas");


        $nome = new TEntry('nome');
        $faltaspermitidas = new TEntry('faltaspermitidas');
        $numeroaulas = new TEntry('numeroaulas');

        $nome->addValidation("O campo nome da Disciplina deve ser preenchido, ", new TRequiredValidator()); 
        $faltaspermitidas->addValidation("O campo Nº de Faltas permitidas na Disciplina deve ser preenchido, ", new TRequiredValidator()); 
        $numeroaulas->addValidation("O campo Nº de Aulas da Disciplina deve ser preenchido, ", new TRequiredValidator()); 

        $nome->setMaxLength(50);
        $nome->setSize('100%');
        $numeroaulas->setSize('100%');
        $faltaspermitidas->setSize('100%');

        $nome->placeholder = "Digite o nome da Disciplina";
        $numeroaulas->placeholder = "Digite o Número de Aulas da Disciplina";
        $faltaspermitidas->placeholder = "Digite o Número de Faltas Permitidas na Disciplina";

        $row1 = $this->form->addFields([new TLabel("Nome da Disciplina:", null, '14px', null)],[$nome]);
        $row2 = $this->form->addFields([new TLabel("Nº de Faltas Permitidas na Disciplina:", null, '14px', null)],[$faltaspermitidas],[new TLabel("Nº de Aulas da Disciplina:", null, '14px', null)],[$numeroaulas]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['DisciplinasHeaderList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Registros","Cadastro de Disciplinas"]));
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

            $object = new Disciplinas(); // create an empty object 

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
            TApplication::loadPage('DisciplinasHeaderList', 'onShow', $loadPageParam); 

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

                $object = new Disciplinas($key); // instantiates the Active Record 

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

