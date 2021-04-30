<?php
/**
 * Holiday Active Record
 * @author  <Franklys Guimaraes>
 */
class Holiday extends TRecord
{
    const TABLENAME = 'holiday';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('name');
        parent::addAttribute('type');
        parent::addAttribute('type_code');
        parent::addAttribute('date');
    }


}



