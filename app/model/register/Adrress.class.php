<?php
/**
 * Adrress Active Record
 * @author  <your-name-here>
 */
class Adrress extends TRecord
{
    const TABLENAME = 'adrress';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $pacient;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('street');
        parent::addAttribute('district');
        parent::addAttribute('city');
        parent::addAttribute('state');
    }

    
    /**
     * Method set_pacient
     * Sample of usage: $adrress->pacient = $object;
     * @param $object Instance of Pacient
     */
    public function set_pacient(Pacient $object)
    {
        $this->pacient = $object;
        $this->pacient_id = $object->id;
    }
    
    /**
     * Method get_pacient
     * Sample of usage: $adrress->pacient->attribute;
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
    


}
