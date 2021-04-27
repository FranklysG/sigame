<?php
/**
 * FullCalendarStaticView
 *
 * @version    1.0
 * @package    Sigame
 * @subpackage Scheduling
 * @author     Franklys Guimarães
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SchedulingCalendar extends TPage
{  
    private $fc;
    
    /**
     * Page constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->fc = new TFullCalendar(date('Y-m-d'), 'month');
        $this->fc->setTimeRange( '06:00:00', '20:00:00' );
        $this->fc->enablePopover( 'Title {title}', '<b>{title}</b> <br> <i class="fa fa-user" aria-hidden="true"></i> {person} <br> {description}');
        
        try {
            TTransaction::open('app');
            $criteria = new TCriteria; 
            $criteria->add(new TFilter('date_format(start_time, "%m")', '=', date('m'))); 
            $repository = new TRepository('Scheduling'); 
            $objects = $repository->load($criteria);
            TTransaction::close();
            
            foreach ($objects as $object) {
                $obj  = (object) ['title'=>'Event 1',  'person' => 'Mary', 'description' => 'Complementary description'];
                // $hexaColor = AppUtil::GenerateHexaColor();
                $this->fc->addEvent($object->id, 'Event', $object->start_time, $object->end_time, null, $object->hexacolor, $obj);
            }

        } catch (Exeption $e) {
            new TMessage('warning', $e->getMessage());
        }
        
        $this->fc->setDayClickAction(new TAction(array($this, 'onDayClick')));
        $this->fc->setEventClickAction(new TAction(array($this, 'onEventClick')));
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->fc);
        parent::add($vbox);
    }
    
    public static function onDayClick($param)
    {
        $date = $param['date'];
        new TMessage('info', "You clicked at date: {$date}");
    }
    
    public static function onEventClick($param)
    {
        $id = $param['id'];
        new TMessage('info', "You clicked at id: {$id}");
    }
}
