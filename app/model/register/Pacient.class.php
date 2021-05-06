<?php
/**
 * Pacient Active Record
 * @author  <your-name-here>
 */
class Pacient extends TRecord
{
    const TABLENAME = 'pacient';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $system_user;
    private $attendances;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('system_user_id');
        parent::addAttribute('pacient_type');
        parent::addAttribute('matrial_status');
        parent::addAttribute('pacient_name');
        parent::addAttribute('mother_name');
        parent::addAttribute('uaps');
        parent::addAttribute('microarea');
        parent::addAttribute('birthday');
        parent::addAttribute('schooling');
        parent::addAttribute('occupation');
        parent::addAttribute('last_menstruation');
        parent::addAttribute('probably_birth');
        parent::addAttribute('normal_birth');
        parent::addAttribute('change_fetal_dev');
        parent::addAttribute('gestational_weight');
        parent::addAttribute('current_weight');
        parent::addAttribute('height');
        parent::addAttribute('reproductive_history');
        parent::addAttribute('journey_day');
        parent::addAttribute('cns');
        parent::addAttribute('medical_record');
        parent::addAttribute('bolsa_familia');
        parent::addAttribute('birth_type');
        parent::addAttribute('created_at');
        parent::addAttribute('updated_at');
    }

    
    /**
     * Method set_system_user
     * Sample of usage: $pacient->system_user = $object;
     * @param $object Instance of SystemUser
     */
    public function set_system_user(SystemUser $object)
    {
        $this->system_user = $object;
        $this->system_user_id = $object->id;
    }
    
    /**
     * Method get_system_user
     * Sample of usage: $pacient->system_user->attribute;
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
     * Method addAttendance
     * Add a Attendance to the Pacient
     * @param $object Instance of Attendance
     */
    public function addAttendance(Attendance $object)
    {
        $this->attendances[] = $object;
    }
    
    /**
     * Method getAttendances
     * Return the Pacient' Attendance's
     * @return Collection of Attendance
     */
    public function getAttendances()
    {
        return $this->attendances;
    }

    /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->attendances = array();
    }

    /**
     * Load the object and its aggregates
     * @param $id object ID
     */
    public function load($id)
    {
    
        // load the related Attendance objects
        $repository = new TRepository('Attendance');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pacient_id', '=', $id));
        $this->attendances = $repository->load($criteria);
    
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
    
        // delete the related Attendance objects
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pacient_id', '=', $this->id));
        $repository = new TRepository('Attendance');
        $repository->load($criteria);
        // store the related Attendance objects
        if ($this->attendances)
        {
            foreach ($this->attendances as $attendance)
            {
                unset($attendance->id);
                $attendance->pacient_id = $this->id;
                $attendance->store();
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
        // delete the related Attendance objects
        $repository = new TRepository('Attendance');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('pacient_id', '=', $id));
        $repository->delete($criteria);
        
    
        // delete the object itself
        parent::delete($id);
    }


}
