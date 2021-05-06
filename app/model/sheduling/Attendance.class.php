<?php
/**
 * Attendance Active Record
 * @author  <your-name-here>
 */
class Attendance extends TRecord
{
    const TABLENAME = 'attendance';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $system_user;
    private $pacient;
    private $forwardings;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('pacient_id');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    
    /**
     * Method set_system_user
     * Sample of usage: $attendance->system_user = $object;
     * @param $object Instance of SystemUser
     */
    public function set_system_user(SystemUser $object)
    {
        $this->system_user = $object;
        $this->system_user_id = $object->id;
    }
    
    /**
     * Method get_system_user
     * Sample of usage: $attendance->system_user->attribute;
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
     * Method set_pacient
     * Sample of usage: $attendance->pacient = $object;
     * @param $object Instance of Pacient
     */
    public function set_pacient(Pacient $object)
    {
        $this->pacient = $object;
        $this->pacient_id = $object->id;
    }
    
    /**
     * Method get_pacient
     * Sample of usage: $attendance->pacient->attribute;
     * @returns Pacient instance
     */
    public function get_pacient()
    {
        // loads the associated object
        if (empty($this->pacient))
            $this->pacient = new Pacient($this->pacient_id);
    
        // returns the associated object
        return $this->pacient;
    }
    
    
    /**
     * Method addForwarding
     * Add a Forwarding to the Attendance
     * @param $object Instance of Forwarding
     */
    public function addForwarding(Forwarding $object)
    {
        $this->forwardings[] = $object;
    }
    
    /**
     * Method getForwardings
     * Return the Attendance' Forwarding's
     * @return Collection of Forwarding
     */
    public function getForwardings()
    {
        return $this->forwardings;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->forwardings = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
    
        // load the related Forwarding objects
        $repository = new TRepository('Forwarding');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('attendance_id', '=', $id));
        $this->forwardings = $repository->load($criteria);
    
        // load the object itself
        return parent::load($id);
    }

    /**
     * Store the object and its aggregates
     */
    public function store()
    {
        // store the object itself
        parent::store();
    
        // delete the related Forwarding objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('attendance_id', '=', $this->id));
        $repository = new TRepository('Forwarding');
        $repository->load($criteria);
        // store the related Forwarding objects
        if ($this->forwardings)
        {
            foreach ($this->forwardings as $forwarding)
            {
                unset($forwarding->id);
                $forwarding->attendance_id = $this->id;
                $forwarding->store();
            }
        }
    }

    /**
     * Delete the object and its aggregates
     * @param $id object ID
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->id;
        // delete the related Forwarding objects
        $repository = new TRepository('Forwarding');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('attendance_id', '=', $id));
        $repository->delete($criteria);
        
    
        // delete the object itself
        parent::delete($id);
    }


}
