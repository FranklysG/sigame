<?php
/**
 * ForwardingForm Form
 * @author  <your name here>
 */
class ForwardingForm extends TPage
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
        $this->form = new BootstrapFormBuilder('form_Forwarding');
        $this->form->setFormTitle('Agendar encaminhamento');
        $this->form->setFieldSizes('100%');
        

        // create the form fields
        $forwarding_id = new THidden('forwarding_id');
        $id = new TEntry('id');
        $system_user_id = new TEntry('system_user_id');
        $attendance_id = new TEntry('attendance_id');
        $start_time = new TDateTime('start_time');
        $end_time = new TDate('end_time');

        // add the fields
        $this->form->addFields( [$forwarding_id] );
        $this->form->addFields( [ new TLabel('N° Encaminhamento'), $id ] );
        $this->form->addFields( [ new TLabel('Resposavel'), $system_user_id ] );
        $this->form->addFields( [ new TLabel('N° Atendimento'), $attendance_id ] );
        $this->form->addFields( [ new TLabel('Data do exame'), $start_time ] );

        $id->setEditable(FALSE);
        $system_user_id->setEditable(FALSE);
        $attendance_id->setEditable(FALSE);
        
        // create the form actions
        // $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save');
        // $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction('Criar agendamento',  new TAction([$this, 'onScheduling']), 'fa:calendar-plus green');
        $this->form->addHeaderActionLink( _t('Close'), new TAction(array($this, 'onClose')), 'fa:times red');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        
        parent::add($container);
    }

    public function onScheduling($param){
        try {
            TTransaction::open('app'); // open a transaction
           
            $this->form->validate(); // validate form data
            $data = $this->form->getData(); // get form data as array
            $holiday = Holiday::where('date(date)','=', date('Y-m-d', strtotime($data->start_time)))->where('type_code','NOT IN', [3,1])->first();
            
            if(empty($holiday) and (date('N') <= 5)){
                $object = Scheduling::where('forwarding_id','=',$data->id)->first();
                if(!$object)
                    $object = new Scheduling;  // create an empty object
                $object->system_user_id = TSession::getValue('userid');
                $object->forwarding_id = $data->id;
                $object->hexacolor = AppUtil::GenerateHexaColor();
                $object->start_time = $data->start_time;
                $object->end_time = date('Y-m-d H:i:s', strtotime('+20 min', strtotime($data->start_time)));
                $object->store(); // save the object
                
                $forwarding = Forwarding::find($data->id);
                if(!$forwarding)
                    $forwarding = new Forwarding;  // create an empty object
                $forwarding->fromArray( (array) $data); // load the object with data
                $forwarding->system_user_id = TSession::getValue('userid');
                $forwarding->store(); // save the object

                // get the generated id
                $data->id = $object->id;
                
                $this->form->setData($data); // fill form data
                TTransaction::close(); // close the transaction
                
                new TMessage('info', 'Agendamento criado com sucesso você sera redirecionado ao calendario', new TAction(['SchedulingCalendar', 'onReload']));
                // AdiantiCoreApplication::loadPage('SchedulingCalendar');
            }else{
                throw new Exception('Parece que essa data é um feriado', 1);
            }
        }
        catch (Exception $e) // in case of exception
        {

            new TMessage('warning', $e->getMessage()); // shows the exception error message
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
                $key = $param['key'];   // get the parameter $key
                TTransaction::open('app'); // open a transaction
                $object = new Forwarding($key); // instantiates the Active Record
                $object->system_user_id = $object->system_user->name; // instantiates the Active Record
                $object->forwarding_id = $object->id; // instantiates the Active Record// instantiates the Active Record
                $obj = $object->getSchedulings();
                if(!empty($obj)){
                    $obj = array_shift($obj);
                    $object->start_time = $obj->start_time;
                }
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
        new TMessage('info', 'Você sera redirecionado para listagem de Encaminhamentos', new TAction(['ForwardingList', 'onReload']));
    }
}
