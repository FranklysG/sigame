<?php
/**
 * SystemRegistrationForm
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SystemRegistrationForm extends TPage
{
    protected $form; // form
    protected $program_list;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new BootstrapFormBuilder('form_registration');
        $this->form->setFormTitle('Registro de conta');
        $this->form->setFieldSizes('100%');
        
        // create the form fields
        $login      = new TEntry('login');
        $login->addValidation( _t('Login'), new TRequiredValidator);
        $name       = new TEntry('name');
        $name->addValidation( _t('Name'), new TRequiredValidator);
        $criteria = new TCriteria();
        $criteria->add(new TFilter('id','NOT IN',[1,2]));
        $group_id       = new TDBCombo('group_id','permission','SystemGroup','id','name', null, $criteria);
        $group_id->addValidation( 'Ocupação', new TRequiredValidator);
        $unit_id       = new TDBCombo('unit_id','permission','SystemUnit','id','name');
        $unit_id->addValidation( 'Unidade', new TRequiredValidator);
        $email      = new TEntry('email');
        $email->addValidation( _t('Email'), new TRequiredValidator);
        $password   = new TPassword('password');
        $password->addValidation( _t('Password'), new TRequiredValidator);
        $repassword = new TPassword('repassword');
        $repassword->addValidation( _t('Password confirmation'), new TRequiredValidator);
        
        $this->form->addAction( _t('Save'),  new TAction([$this, 'onSave']), 'far:save')->{'class'} = 'btn btn-sm bg-purple';
        $this->form->addAction( _t('Clear'), new TAction([$this, 'onClear']), 'fa:eraser red' );
        $this->form->addAction( _t('Back'),  new TAction(['LoginForm','onLoad']), 'far:arrow-alt-circle-left blue' );
        
        
        $row = $this->form->addFields( [new TLabel(_t('Login')),$login]
                                ,[new TLabel(_t('Name')), $name]
                                ,[new TLabel('Ocupação'), $group_id]
                                ,[new TLabel('Unidade'), $unit_id]
                                ,[new TLabel(_t('Email')),$email]
                                ,[new TLabel(_t('Password')), $password]
                                ,[new TLabel(_t('Password confirmation')), $repassword]
                             );
        $row->layout = ['col-sm-6','col-sm-6','col-sm-6','col-sm-6','col-sm-12','col-sm-6','col-sm-6'];

        // add the container to the page
        $wrapper = new TElement('div');
        $wrapper->style = 'margin:auto; margin-top:100px;max-width:600px;';
        // $wrapper->id    = 'login-wrapper';
        $wrapper->add($this->form);
        
        // add the wrapper to the page
        parent::add($wrapper);
    }
    
    /**
     * Clear form
     */
    public function onClear()
    {
        $this->form->clear( true );
    }
    
    /**
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    public static function onSave($param)
    {
        try
        {
            $ini = AdiantiApplicationConfig::get();
            if ($ini['permission']['user_register'] !== '1')
            {
                throw new Exception( _t('The user registration is disabled') );
            }
            
            // open a transaction with database 'permission'
            TTransaction::open('permission');
            
            if( empty($param['login']) )
            {
                throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Login')));
            }
            
            if( empty($param['name']) )
            {
                throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Name')));
            }
            
            if( empty($param['email']) )
            {
                throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Email')));
            }
            
            if( empty($param['password']) )
            {
                throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Password')));
            }
            
            if( empty($param['repassword']) )
            {
                throw new Exception(TAdiantiCoreTranslator::translate('The field ^1 is required', _t('Password confirmation')));
            }
            
            if (SystemUser::newFromLogin($param['login']) instanceof SystemUser)
            {
                throw new Exception(_t('An user with this login is already registered'));
            }
            
            if (SystemUser::newFromEmail($param['email']) instanceof SystemUser)
            {
                throw new Exception(_t('An user with this e-mail is already registered'));
            }
            
            if( $param['password'] !== $param['repassword'] )
            {
                throw new Exception(_t('The passwords do not match'));
            }
            
            $object = new SystemUser;
            $object->active = 'N';
            $object->fromArray( $param );
            $object->password = md5($object->password);
            $object->system_unit_id = $param['unit_id'];
            $object->frontpage_id = $ini['permission']['default_screen'];
            $object->clearParts();
            $object->store();

            $object->addSystemUserGroup(SystemGroup::find($param['group_id']));
            $object->addSystemUserUnit(SystemUnit::find($param['unit_id']));
            
            TTransaction::close(); // close the transaction
            $pos_action = new TAction(['LoginForm', 'onLoad']);
            new TMessage('info', 'Aguarde até que o responsavel libere o acesso a sua conta', $pos_action); // shows the success message
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
