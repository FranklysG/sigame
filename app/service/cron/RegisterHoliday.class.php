<?php
/**
 * RegisterHoliday
 *
 * @version    1.0
 * @package    control
 * @subpackage public
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class RegisterHoliday extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
    }

    public function register(){
        try{
            TTransaction::open('app');
            $preference = SystemPreference::find('link_api');
            
            if(!empty($preference->value)){
                $year = date('Y');
                $url = $preference->value."?json=true&ano={$year}&ibge=2101400&token=cGFyb3ZhNTQ2MUBvcmFtYWlsLm5ldCZoYXNoPTE3MzYxMjM0Mw";
                $holiday = AppUtil::url_get_contents($url);

                // delete the related SoccerTable objects
                $criteria = new TCriteria;
                $criteria->add(new TFilter('id', '>=', 0));
                $repository = new TRepository('Holiday');
                $repository->delete($criteria);
                
                foreach ($holiday as $value) {
                    $object = new Holiday;
                    $object->date = Convert::toDateUS($value->date);
                    $object->name = $value->name;
                    $object->type = $value->type;
                    $object->type_code = $value->type_code;
                    $object->store();
                }
                new TMessage('info', 'Feriados salvos');
            }else{
                throw new Exception("", 1);
            }

            TTransaction::close();
           
        }catch(Exeption $e){
            new TMessage('warning', 'Parece que o link da api não está disponivel', $e->getMessage());
            TTransaction::rollback();
        }
    }
}
