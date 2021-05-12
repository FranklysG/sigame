<?php
/**
 * AttendanceForm Form
 * @author  <your name here>
 */
class AttendanceForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        parent::setTargetContainer('adianti_right_panel');
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Attendance');
        $this->form->setFormTitle('Atendimento do paciente');
        $this->form->setFieldSizes('100%');
        

        // create the form fields
        $id = new TEntry('id');
        $system_user_id = new TEntry('system_user_id');
        $pacient_id = new TDBUniqueSearch('pacient_id', 'app', 'Pacient', 'id', 'pacient_name');
        $pacient_id->addValidation('Paciente', new TRequiredValidator);
        $pacient_id->setMinLength(0);


        // add the fields
        $this->form->addFields( [ new TLabel('N° Atendimento'), $id ] );
        $this->form->addFields( [ new TLabel('Responsavel'), $system_user_id ] );
        $this->form->addFields( [ new TLabel('Paciente'), $pacient_id ] );

        $id->setEditable(FALSE);
        $system_user_id->setEditable(FALSE);
        
        // create the form actions
        $btn = $this->form->addAction('Salvar atendimento', new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm bg-purple';
        // $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        $this->form->addHeaderActionLink( _t('Close'), new TAction(array($this, 'onClose')), 'fa:times red');

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('app'); // open a transaction
            
            /**
            // Enable Debug logger for SQL operations inside the transaction
            TTransaction::setLogger(new TLoggerSTD); // standard output
            TTransaction::setLogger(new TLoggerTXT('log.txt')); // file
            **/
            
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            
            $object = new Attendance;  // create an empty object
            $object->fromArray( (array) $data); // load the object with data
            $object->system_user_id = TSession::getValue('userid'); 
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'), new TAction(['AttendanceList', 'onReload']));
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('app'); // open a transaction
                $object = new Attendance($key); // instantiates the Active Record
                $object->system_user_id = $object->system_user->name; // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
        new TMessage('info', 'Você sera redirecionado para listagem de atendimentos', new TAction(['AttendanceList', 'onReload']));
    }
}
