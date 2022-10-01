<?php

class TurmasForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'teste';
    private static $activeRecord = 'Turmas';
    private static $primaryKey = 'id';
    private static $formName = 'form_TurmasForm';

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
        $this->form->setFormTitle("Cadastro de Turmas");


        $idinstituicao = new TDBCombo('idinstituicao', 'teste', 'Instituicoes', 'id', '{razaosocial}','razaosocial asc'  );
        $idcursodisciplina = new TDBCombo('idcursodisciplina', 'teste', 'Cursoxdisciplina', 'id', '{fk_idcursos->nome} / {fk_iddisciplinas->nome} ','id asc'  );
        $nome = new TEntry('nome');
        $idprofessores = new TDBCombo('idprofessores', 'teste', 'Professores', 'id', '{nome}','nome asc'  );
        $idalunosxturmas = new TDBCombo('idalunosxturmas', 'teste', 'Alunos', 'matricula', '{nome}','nome asc'  );

        $idinstituicao->addValidation("O campo Instituição deve ser selecionado, ", new TRequiredValidator()); 
        $idcursodisciplina->addValidation("O campo Curso/Disciplina deve ser selecionado, ", new TRequiredValidator()); 
        $nome->addValidation("O campo Nome da Turma deve ser preenchido, ", new TRequiredValidator()); 
        $idprofessores->addValidation("O campo Professor deve ser selecionado, ", new TRequiredValidator()); 
        $idalunosxturmas->addValidation("O campo Aluno deve ser selecionado, ", new TRequiredValidator()); 

        $nome->setMaxLength(50);

        $nome->placeholder = "Digite o nome da Turma";

        $idinstituicao->enableSearch();
        $idprofessores->enableSearch();
        $idalunosxturmas->enableSearch();
        $idcursodisciplina->enableSearch();

        $nome->setSize('100%');
        $idinstituicao->setSize('100%');
        $idprofessores->setSize('100%');
        $idalunosxturmas->setSize('100%');
        $idcursodisciplina->setSize('100%');

        $row1 = $this->form->addFields([new TLabel("Instituição:", null, '14px', null)],[$idinstituicao]);
        $row2 = $this->form->addFields([new TLabel("Curso/Disciplina:", null, '14px', null)],[$idcursodisciplina]);
        $row3 = $this->form->addFields([new TLabel("Nome da Turma:", null, '14px', null)],[$nome]);
        $row4 = $this->form->addFields([new TLabel("Professor:", null, '14px', null)],[$idprofessores]);
        $row5 = $this->form->addFields([new TLabel("Aluno:", null, '14px', null)],[$idalunosxturmas]);

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave']), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['TurmasHeaderList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->class = 'form-container';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Registros","Cadastro de Turmas"]));
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

            $object = new Turmas(); // create an empty object 

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
            TApplication::loadPage('TurmasHeaderList', 'onShow', $loadPageParam); 

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

                $object = new Turmas($key); // instantiates the Active Record 

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

