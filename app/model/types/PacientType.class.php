<?php
/**
 * PacienteType Active Record
 * @author  <your-name-here>
 */
class PacienteType extends TRecord
{
    const TABLENAME = 'paciente_type';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
    }


}
