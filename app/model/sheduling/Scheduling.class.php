<?php
/**
 * Scheduling Active Record
 * @author  <your-name-here>
 */
class Scheduling extends TRecord
{
    const TABLENAME = 'scheduling';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $system_user;
    private $forwarding;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('forwarding_id');
        parent::addAttribute('hexacolor');
        parent::addAttribute('start_time');
        parent::addAttribute('end_time');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    
    /**
     * Method set_system_user
     * Sample of usage: $scheduling->system_user = $object;
     * @param $object Instance of SystemUser
     */
    public function set_system_user(SystemUser $object)
    {
        $this->system_user = $object;
        $this->system_user_id = $object->id;
    }
    
    /**
     * Method get_system_user
     * Sample of usage: $scheduling->system_user->attribute;
     * @returns SystemUser instance
     */
    public function get_system_user()
    {
        // loads the associated object
        if (empty($this->system_user))
            $this->system_user = new SystemUser($this->system_user_id);
    
        // returns the associated object
        return $this->system_user;
    }
    
    
    /**
     * Method set_forwarding
     * Sample of usage: $scheduling->forwarding = $object;
     * @param $object Instance of Forwarding
     */
    public function set_forwarding(Forwarding $object)
    {
        $this->forwarding = $object;
        $this->forwarding_id = $object->id;
    }
    
    /**
     * Method get_forwarding
     * Sample of usage: $scheduling->forwarding->attribute;
     * @returns Forwarding instance
     */
    public function get_forwarding()
    {
        // loads the associated object
        if (empty($this->forwarding))
            $this->forwarding = new Forwarding($this->forwarding_id);
    
        // returns the associated object
        return $this->forwarding;
    }
    


}
