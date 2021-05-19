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
        parent::setTargetContainer('adianti_right_panel');
        
        
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
        $pacient_type = new TCombo('pacient_type');
        $pacient_type->addItems([
            '0' => 'Criança',
            '1' => 'Gestante',
        ]);
        $pacient_type->setValue(1);
        $pacient_type->setChangeAction(new TAction(array($this, 'onChangeType')));
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
        $this->form->addFields( [ new TLabel('N°'), $id ], [ new TLabel('Tipo Paciente'), $pacient_type ] );
        $this->form->addFields( [ new TLabel('NOME'), $pacient_name ] );
        $this->form->addFields( [ new TLabel('NOME DA MÃE'), $mother_name ] );
        $this->form->addFields( [ new TLabel('ESTADO CIVIL'), $matrial_status ] );
        $this->form->addFields( [ new TLabel('UAPS'), $uaps ] );
        $this->form->addFields( [ new TLabel('MICROAREA'), $microarea ] );
        $this->form->addFields( [ new TLabel('DATA DE NASCIMENTO'), $birthday ] );
        $this->form->addFields( [ new TLabel('GRAU DE ESCOLARIDADE'), $schooling ] );
        $this->form->addFields( [ new TLabel('OCUPAÇÃO'), $occupation ] );
        $this->form->addFields( [ new TLabel('ULTIMA MENSTRUAÇÃO'), $last_menstruation ] );
        $this->form->addFields( [ new TLabel('DATA DO NASCIMENTO'), $probably_birth ] );
        $this->form->addFields( [ new TLabel('PARTO NORMAL'), $normal_birth ] );
        $this->form->addFields( [ new TLabel('ALTERAÇÃO NO DESENVOLVIMENTO FETAL'), $change_fetal_dev ] );
        $this->form->addFields( [ new TLabel('PESO PRÉ-GESTACIONAL'), $gestational_weight ] );
        $this->form->addFields( [ new TLabel('PESO ATUAL'), $current_weight ] );
        $this->form->addFields( [ new TLabel('ALTURA'), $height ] );
        $this->form->addFields( [ new TLabel('HISTORICO REPRODUTIVO'), $reproductive_history ] );
        $this->form->addFields( [ new TLabel('JORNADA / DIA'), $journey_day ] );
        $this->form->addFields( [ new TLabel('CNS'), $cns ] );
        $this->form->addFields( [ new TLabel('PRONTUARIO'), $medical_record ] );
        $this->form->addFields( [ new TLabel('BOLSA FAMILIA'), $bolsa_familia ] );
        $this->form->addFields( [ new TLabel('TIPO DE PARTO'), $birth_type ] );

        $id->setEditable(FALSE);
       
        
        if(isset($param['form_editable']))
            $this->form->setEditable(false);
        

        // create the form actions
        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        $btn->class = 'btn btn-sm bg-purple';
        $this->form->addActionLink(_t('New'),  new TAction([$this, 'onEdit']), 'fa:eraser red');
        $this->form->addHeaderActionLink( _t('Close'), new TAction(array($this, 'onClose')), 'fa:times red');
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    public static function onChangeType($param)
    {
        if ($param['pacient_type'] == '0')
        {
            TQuickForm::hideField('form_Pacient', 'matrial_status');
            TQuickForm::showField('form_Pacient', 'pacient_name');
            TQuickForm::showField('form_Pacient', 'mother_name');
            TQuickForm::showField('form_Pacient', 'uaps');
            TQuickForm::showField('form_Pacient', 'microarea');
            TQuickForm::showField('form_Pacient', 'birthday');
            TQuickForm::hideField('form_Pacient', 'schooling');
            TQuickForm::hideField('form_Pacient', 'occupation');
            TQuickForm::hideField('form_Pacient', 'last_menstruation');
            TQuickForm::hideField('form_Pacient', 'probably_birth');
            TQuickForm::hideField('form_Pacient', 'normal_birth');
            TQuickForm::hideField('form_Pacient', 'change_fetal_dev');
            TQuickForm::hideField('form_Pacient', 'gestational_weight');
            TQuickForm::showField('form_Pacient', 'current_weight');
            TQuickForm::showField('form_Pacient', 'height');
            TQuickForm::hideField('form_Pacient', 'reproductive_history');
            TQuickForm::hideField('form_Pacient', 'journey_day');
            TQuickForm::showField('form_Pacient', 'cns');
            TQuickForm::showField('form_Pacient', 'medical_record');
            TQuickForm::hideField('form_Pacient', 'bolsa_familia');
            TQuickForm::hideField('form_Pacient', 'birth_type');
        }
        else
        {
            TQuickForm::showField('form_Pacient', 'matrial_status');
            TQuickForm::showField('form_Pacient', 'pacient_name');
            TQuickForm::hideField('form_Pacient', 'mother_name');
            TQuickForm::hideField('form_Pacient', 'uaps');
            TQuickForm::hideField('form_Pacient', 'microarea');
            TQuickForm::hideField('form_Pacient', 'birthday');
            TQuickForm::showField('form_Pacient', 'schooling');
            TQuickForm::showField('form_Pacient', 'occupation');
            TQuickForm::showField('form_Pacient', 'last_menstruation');
            TQuickForm::showField('form_Pacient', 'probably_birth');
            TQuickForm::showField('form_Pacient', 'normal_birth');
            TQuickForm::showField('form_Pacient', 'change_fetal_dev');
            TQuickForm::showField('form_Pacient', 'gestational_weight');
            TQuickForm::hideField('form_Pacient', 'current_weight');
            TQuickForm::hideField('form_Pacient', 'height');
            TQuickForm::showField('form_Pacient', 'reproductive_history');
            TQuickForm::showField('form_Pacient', 'journey_day');
            TQuickForm::hideField('form_Pacient', 'cns');
            TQuickForm::hideField('form_Pacient', 'medical_record');
            TQuickForm::showField('form_Pacient', 'bolsa_familia');
            TQuickForm::showField('form_Pacient', 'birth_type');
        }
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
                
                // TForm::sendData('form_Pacient', $object->pacient_type);
                $this->form->setData($object); // fill the form
                
                $param['pacient_type'] = $object->pacient_type;
                $this->onChangeType($param);
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
        if(isset($param['register_state'])){
            new TMessage('info', 'Você sera redirecionado para o <strong>AGENDAMENTO</strong> de clinetes', new TAction(['SchedulingCalendar', 'onReload']));
        }else{
            new TMessage('info', 'Você sera redirecionado para <strong>LISTAGEM</strong> de pacientes', new TAction(['PacientList', 'onReload']));
        }
               
    }
}
