<?php

class RegistropresencaForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'teste';
    private static $activeRecord = 'Registropresenca';
    private static $primaryKey = 'id';
    private static $formName = 'form_RegistropresencaForm';

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
        $this->form->setFormTitle("Registro de Presenças");


        $datapresenca = new TEntry('datapresenca');
        $presenca = new TCombo('presenca');
        $idalunosturma = new TDBCombo('idalunosturma', 'teste', 'Alunos', 'matricula', '{nome}','nome asc'  );

        $datapresenca->addValidation("O campo data da presença deve ser preenchido, ", new TRequiredValidator()); 
        $presenca->addValidation("Marque a presença, ", new TRequiredValidator()); 
        $idalunosturma->addValidation("Selecione o Aluno, ", new TRequiredValidator()); 

        $datapresenca->setMask('00/00/0000');
        $presenca->addItems(["T"=>" Presença","F"=>" Falta"]);
        $presenca->setDefaultOption(false);
        $idalunosturma->enableSearch();

        $datapresenca->placeholder = "Preencha a data completa do dia da aula (Ex: 22/02/2022)";

        $presenca->setSize('100%');
        $datapresenca->setSize('100%');
        $idalunosturma->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Data da Aula:", null, '14px', null)],[$datapresenca]);
        $row2 = $this->form->addFields([new TLabel("Presença:", null, '14px', null)],[$presenca]);
        $row3 = $this->form->addFields([new TLabel("Selecione o Aluno:", null, '14px', null)],[$idalunosturma]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['RegistropresencaHeaderList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Registros","Registro de Presenças"]));
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

            $object = new Registropresenca(); // create an empty object 

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
            TApplication::loadPage('RegistropresencaHeaderList', 'onShow', $loadPageParam); 

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

                $object = new Registropresenca($key); // instantiates the Active Record 

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

