<?php
/**
 * PacientForm Form
 * @author  <your name here>
 */
class PacientForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_Pacient');
        $this->form->setFormTitle('Cadastro de Paciente');
        $this->form->setFieldSizes('100%');
        
        // create the itens of combo
        $yes_or_no = [
            '0' => 'Não',
            '1' => 'Sim'
        ];

        // create the form fields
        $id = new TEntry('id');
        $type_paciente_id = new TDBUniqueSearch('type_paciente_id','app', 'PacienteType', 'id', 'name');
        $type_paciente_id->setMinLength(0);
        $matrial_status = new TCombo('matrial_status');
        $matrial_status->addItems([
            '0' => 'Solteiro(a)',
            '1' => 'Casado(a)',
            '2' => 'Divorciado(a)',
            '3' => 'Viuvo(a)'
        ]);
        $pacient_name = new TEntry('pacient_name');
        $mother_name = new TEntry('mother_name');
        $uaps = new TEntry('uaps');
        $microarea = new TEntry('microarea');
        $birthday = new TDate('birthday');
        $schooling = new TCombo('schooling');
        $schooling->addItems([
            '0' => 'Ensino Fundamental',
            '1' => 'Ensino Fundamental Incompleto',
            '2' => 'Ensino Médio',
            '3' => 'Ensino Médio Incompleto',
            '4' => 'Ensino Médio Completo',
            '5' => 'Ensino Superior',
            '6' => 'Ensino Superior Incompleto',
            '7' => 'Ensino Superior Completo',
        ]);
        $occupation = new TEntry('occupation');
        $last_menstruation = new TDate('last_menstruation');
        $probably_birth = new TDate('probably_birth');
        $normal_birth = new TCombo('normal_birth');
        $normal_birth->addItems($yes_or_no);
        $change_fetal_dev = new TCombo('change_fetal_dev');
        $change_fetal_dev->addItems($yes_or_no);
        $gestational_weight = new TEntry('gestational_weight');
        $current_weight = new TEntry('current_weight');
        $height = new TEntry('height');
        $reproductive_history = new TCombo('reproductive_history');
        $reproductive_history->addItems($yes_or_no);
        $journey_day = new TEntry('journey_day');
        $cns = new TEntry('cns');
        $medical_record = new TEntry('medical_record');
        $bolsa_familia = new TCombo('bolsa_familia');
        $bolsa_familia->addItems($yes_or_no);
        $birth_type = new TCombo('birth_type');
        $birth_type->addItems($yes_or_no);


        // add the fields
        $this->form->addFields( [ new TLabel('N° Pacinte'), $id ], [ new TLabel('Tipo Paciente'), $type_paciente_id ] );
        $this->form->addFields( [ new TLabel('Estado civil'), $matrial_status ] );
        $this->form->addFields( [ new TLabel('Nome'), $pacient_name ] );
        $this->form->addFields( [ new TLabel('Nome da mãe'), $mother_name ] );
        $this->form->addFields( [ new TLabel('Uaps'), $uaps ] );
        $this->form->addFields( [ new TLabel('Microarea'), $microarea ] );
        $this->form->addFields( [ new TLabel('Data de nascimento'), $birthday ] );
        $this->form->addFields( [ new TLabel('Grau de escolaridade'), $schooling ] );
        $this->form->addFields( [ new TLabel('Ocupação'), $occupation ] );
        $this->form->addFields( [ new TLabel('Ultima Menstruação'), $last_menstruation ] );
        $this->form->addFields( [ new TLabel('Data do Nascimento'), $probably_birth ] );
        $this->form->addFields( [ new TLabel('Parto Normal'), $normal_birth ] );
        $this->form->addFields( [ new TLabel('Alteração no desenvolvimento fetal'), $change_fetal_dev ] );
        $this->form->addFields( [ new TLabel('Peso Pré-Gestacional'), $gestational_weight ] );
        $this->form->addFields( [ new TLabel('Peso Atual'), $current_weight ] );
        $this->form->addFields( [ new TLabel('Altura'), $height ] );
        $this->form->addFields( [ new TLabel('Historico reprodutivo'), $reproductive_history ] );
        $this->form->addFields( [ new TLabel('Jornada / dia'), $journey_day ] );
        $this->form->addFields( [ new TLabel('Cns'), $cns ] );
        $this->form->addFields( [ new TLabel('Prontuario'), $medical_record ] );
        $this->form->addFields( [ new TLabel('Bolsa Familia'), $bolsa_familia ] );
        $this->form->addFields( [ new TLabel('Tipo de parto'), $birth_type ] );
        $this->form->addFields( [ new TLabel('Data do Registro'), $created_at ] );

        $id->setEditable(FALSE);
        if (!empty($id))
        {
        }
        
        /** samples
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( '100%' ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        
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
            
            $object = new Pacient;  // create an empty object
            $object->system_user_id = TSession::getValue('userid');
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', AdiantiCoreTranslator::translate('Record saved'));
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
                $object = new Pacient($key); // instantiates the Active Record
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
}
