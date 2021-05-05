<?php
/**
 * PacientList Listing
 * @author  <your name here>
 */
class PacientList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_search_Pacient');
        $this->form->setFormTitle('Pacient');
        

        // create the form fields
        $system_user_id = new TDBUniqueSearch('system_user_id', 'app', 'SystemUser', 'id', 'name');
        $type_paciente_id = new TEntry('type_paciente_id');
        $pacient_name = new TEntry('pacient_name');
        $mother_name = new TEntry('mother_name');
        $uaps = new TEntry('uaps');
        $birthday = new TEntry('birthday');
        $cns = new TEntry('cns');
        $bolsa_familia = new TEntry('bolsa_familia');


        // add the fields
        $this->form->addFields( [ new TLabel('System User Id') ], [ $system_user_id ] );
        $this->form->addFields( [ new TLabel('Type Paciente Id') ], [ $type_paciente_id ] );
        $this->form->addFields( [ new TLabel('Pacient Name') ], [ $pacient_name ] );
        $this->form->addFields( [ new TLabel('Mother Name') ], [ $mother_name ] );
        $this->form->addFields( [ new TLabel('Uaps') ], [ $uaps ] );
        $this->form->addFields( [ new TLabel('Birthday') ], [ $birthday ] );
        $this->form->addFields( [ new TLabel('Cns') ], [ $cns ] );
        $this->form->addFields( [ new TLabel('Bolsa Familia') ], [ $bolsa_familia ] );


        // set sizes
        $system_user_id->setSize('100%');
        $type_paciente_id->setSize('100%');
        $pacient_name->setSize('100%');
        $mother_name->setSize('100%');
        $uaps->setSize('100%');
        $birthday->setSize('100%');
        $cns->setSize('100%');
        $bolsa_familia->setSize('100%');

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__ . '_filter_data') );
        
        // add the search form actions
        $btn = $this->form->addAction(_t('Find'), new TAction([$this, 'onSearch']), 'fa:search');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction(['PacientForm', 'onEdit']), 'fa:plus green');
        
        // creates a Datagrid
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'right');
        $column_system_user_id = new TDataGridColumn('system_user_id', 'System User Id', 'right');
        $column_type_paciente_id = new TDataGridColumn('type_paciente_id', 'Type Paciente Id', 'right');
        $column_matrial_status = new TDataGridColumn('matrial_status', 'Matrial Status', 'left');
        $column_pacient_name = new TDataGridColumn('pacient_name', 'Pacient Name', 'left');
        $column_mother_name = new TDataGridColumn('mother_name', 'Mother Name', 'left');
        $column_uaps = new TDataGridColumn('uaps', 'Uaps', 'left');
        $column_microarea = new TDataGridColumn('microarea', 'Microarea', 'left');
        $column_birthday = new TDataGridColumn('birthday', 'Birthday', 'left');
        $column_schooling = new TDataGridColumn('schooling', 'Schooling', 'left');
        $column_occupation = new TDataGridColumn('occupation', 'Occupation', 'left');
        $column_last_menstruation = new TDataGridColumn('last_menstruation', 'Last Menstruation', 'left');
        $column_probably_birth = new TDataGridColumn('probably_birth', 'Probably Birth', 'left');
        $column_normal_birth = new TDataGridColumn('normal_birth', 'Normal Birth', 'left');
        $column_change_fetal_dev = new TDataGridColumn('change_fetal_dev', 'Change Fetal Dev', 'left');
        $column_gestational_weight = new TDataGridColumn('gestational_weight', 'Gestational Weight', 'left');
        $column_current_weight = new TDataGridColumn('current_weight', 'Current Weight', 'left');
        $column_height = new TDataGridColumn('height', 'Height', 'left');
        $column_reproductive_history = new TDataGridColumn('reproductive_history', 'Reproductive History', 'left');
        $column_journey_day = new TDataGridColumn('journey_day', 'Journey Day', 'left');
        $column_cns = new TDataGridColumn('cns', 'Cns', 'left');
        $column_medical_record = new TDataGridColumn('medical_record', 'Medical Record', 'left');
        $column_bolsa_familia = new TDataGridColumn('bolsa_familia', 'Bolsa Familia', 'left');
        $column_birth_type = new TDataGridColumn('birth_type', 'Birth Type', 'left');
        $column_created_at = new TDataGridColumn('created_at', 'Created At', 'left');
        $column_updated_at = new TDataGridColumn('updated_at', 'Updated At', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_system_user_id);
        $this->datagrid->addColumn($column_type_paciente_id);
        $this->datagrid->addColumn($column_matrial_status);
        $this->datagrid->addColumn($column_pacient_name);
        $this->datagrid->addColumn($column_mother_name);
        $this->datagrid->addColumn($column_uaps);
        $this->datagrid->addColumn($column_microarea);
        $this->datagrid->addColumn($column_birthday);
        $this->datagrid->addColumn($column_schooling);
        $this->datagrid->addColumn($column_occupation);
        $this->datagrid->addColumn($column_last_menstruation);
        $this->datagrid->addColumn($column_probably_birth);
        $this->datagrid->addColumn($column_normal_birth);
        $this->datagrid->addColumn($column_change_fetal_dev);
        $this->datagrid->addColumn($column_gestational_weight);
        $this->datagrid->addColumn($column_current_weight);
        $this->datagrid->addColumn($column_height);
        $this->datagrid->addColumn($column_reproductive_history);
        $this->datagrid->addColumn($column_journey_day);
        $this->datagrid->addColumn($column_cns);
        $this->datagrid->addColumn($column_medical_record);
        $this->datagrid->addColumn($column_bolsa_familia);
        $this->datagrid->addColumn($column_birth_type);
        $this->datagrid->addColumn($column_created_at);
        $this->datagrid->addColumn($column_updated_at);


        $action1 = new TDataGridAction(['PacientForm', 'onEdit'], ['id'=>'{id}']);
        $action2 = new TDataGridAction([$this, 'onDelete'], ['id'=>'{id}']);
        
        $this->datagrid->addAction($action1, _t('Edit'),   'far:edit blue');
        $this->datagrid->addAction($action2 ,_t('Delete'), 'far:trash-alt red');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction([$this, 'onReload']));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add($this->form);
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    
    /**
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    public function onInlineEdit($param)
    {
        try
        {
            // get the parameter $key
            $field = $param['field'];
            $key   = $param['key'];
            $value = $param['value'];
            
            TTransaction::open('app'); // open a transaction with database
            $object = new Pacient($key); // instantiates the Active Record
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue(__CLASS__.'_filter_system_user_id',   NULL);
        TSession::setValue(__CLASS__.'_filter_type_paciente_id',   NULL);
        TSession::setValue(__CLASS__.'_filter_pacient_name',   NULL);
        TSession::setValue(__CLASS__.'_filter_mother_name',   NULL);
        TSession::setValue(__CLASS__.'_filter_uaps',   NULL);
        TSession::setValue(__CLASS__.'_filter_birthday',   NULL);
        TSession::setValue(__CLASS__.'_filter_cns',   NULL);
        TSession::setValue(__CLASS__.'_filter_bolsa_familia',   NULL);

        if (isset($data->system_user_id) AND ($data->system_user_id)) {
            $filter = new TFilter('system_user_id', '=', $data->system_user_id); // create the filter
            TSession::setValue(__CLASS__.'_filter_system_user_id',   $filter); // stores the filter in the session
        }


        if (isset($data->type_paciente_id) AND ($data->type_paciente_id)) {
            $filter = new TFilter('type_paciente_id', 'like', "%{$data->type_paciente_id}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_type_paciente_id',   $filter); // stores the filter in the session
        }


        if (isset($data->pacient_name) AND ($data->pacient_name)) {
            $filter = new TFilter('pacient_name', 'like', "%{$data->pacient_name}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_pacient_name',   $filter); // stores the filter in the session
        }


        if (isset($data->mother_name) AND ($data->mother_name)) {
            $filter = new TFilter('mother_name', 'like', "%{$data->mother_name}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_mother_name',   $filter); // stores the filter in the session
        }


        if (isset($data->uaps) AND ($data->uaps)) {
            $filter = new TFilter('uaps', 'like', "%{$data->uaps}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_uaps',   $filter); // stores the filter in the session
        }


        if (isset($data->birthday) AND ($data->birthday)) {
            $filter = new TFilter('birthday', 'like', "%{$data->birthday}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_birthday',   $filter); // stores the filter in the session
        }


        if (isset($data->cns) AND ($data->cns)) {
            $filter = new TFilter('cns', 'like', "%{$data->cns}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_cns',   $filter); // stores the filter in the session
        }


        if (isset($data->bolsa_familia) AND ($data->bolsa_familia)) {
            $filter = new TFilter('bolsa_familia', 'like', "%{$data->bolsa_familia}%"); // create the filter
            TSession::setValue(__CLASS__.'_filter_bolsa_familia',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue(__CLASS__ . '_filter_data', $data);
        
        $param = array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'app'
            TTransaction::open('app');
            
            // creates a repository for Pacient
            $repository = new TRepository('Pacient');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue(__CLASS__.'_filter_system_user_id')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_system_user_id')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_type_paciente_id')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_type_paciente_id')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_pacient_name')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_pacient_name')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_mother_name')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_mother_name')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_uaps')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_uaps')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_birthday')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_birthday')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_cns')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_cns')); // add the session filter
            }


            if (TSession::getValue(__CLASS__.'_filter_bolsa_familia')) {
                $criteria->add(TSession::getValue(__CLASS__.'_filter_bolsa_familia')); // add the session filter
            }

            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public static function onDelete($param)
    {
        // define the delete action
        $action = new TAction([__CLASS__, 'Delete']);
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public static function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('app'); // open a transaction with database
            $object = new Pacient($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            
            $pos_action = new TAction([__CLASS__, 'onReload']);
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted'), $pos_action); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }
}
