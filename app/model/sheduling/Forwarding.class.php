<?php
/**
 * Forwarding Active Record
 * @author  <your-name-here>
 */
class Forwarding extends TRecord
{
    const TABLENAME = 'forwarding';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $system_user;
    private $attendance;
    private $schedulings;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('attendance_id');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    
    /**
     * Method set_system_user
     * Sample of usage: $forwarding->system_user = $object;
     * @param $object Instance of SystemUser
     */
    public function set_system_user(SystemUser $object)
    {
        $this->system_user = $object;
        $this->system_user_id = $object->id;
    }
    
    /**
     * Method get_system_user
     * Sample of usage: $forwarding->system_user->attribute;
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
     * Method set_attendance
     * Sample of usage: $forwarding->attendance = $object;
     * @param $object Instance of Attendance
     */
    public function set_attendance(Attendance $object)
    {
        $this->attendance = $object;
        $this->attendance_id = $object->id;
    }
    
    /**
     * Method get_attendance
     * Sample of usage: $forwarding->attendance->attribute;
     * @returns Attendance instance
     */
    public function get_attendance()
    {
        // loads the associated object
        if (empty($this->attendance))
            $this->attendance = new Attendance($this->attendance_id);
    
        // returns the associated object
        return $this->attendance;
    }
    
    
    /**
     * Method addScheduling
     * Add a Scheduling to the Forwarding
     * @param $object Instance of Scheduling
     */
    public function addScheduling(Scheduling $object)
    {
        $this->schedulings[] = $object;
    }
    
    /**
     * Method getSchedulings
     * Return the Forwarding' Scheduling's
     * @return Collection of Scheduling
     */
    public function getSchedulings()
    {
        return $this->schedulings;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->schedulings = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
    
        // load the related Scheduling objects
        $repository = new TRepository('Scheduling');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('forwarding_id', '=', $id));
        $this->schedulings = $repository->load($criteria);
    
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
    
        // delete the related Scheduling objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('forwarding_id', '=', $this->id));
        $repository = new TRepository('Scheduling');
        $repository->delete($criteria);
        // store the related Scheduling objects
        if ($this->schedulings)
        {
            foreach ($this->schedulings as $scheduling)
            {
                unset($scheduling->id);
                $scheduling->forwarding_id = $this->id;
                $scheduling->store();
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
        // delete the related Scheduling objects
        $repository = new TRepository('Scheduling');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('forwarding_id', '=', $id));
        $repository->delete($criteria);
        
    
        // delete the object itself
        parent::delete($id);
    }


}
