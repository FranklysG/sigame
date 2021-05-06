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
        $this->fc->setOption('businessHours', [ [ 'dow' => [ 1, 2, 3, 4, 5 ]]]);
        $this->fc->setTimeRange('08:00', '17:00');
        $this->fc->disableDragging();
        $this->fc->disableResizing();
        $this->fc->enablePopover( '{ocupation}(a): {title}', '<i class="fa fa-user" aria-hidden="true"></i> {person} <br> <i class="fa fa-clock" aria-hidden="true"></i> {description}');
        
        try {
            TTransaction::open('app');
            $criteria = new TCriteria; 
            $criteria->add(new TFilter('date_format(start_time, "%m")', '=', date('m'))); 
            $repository = new TRepository('Scheduling'); 
            $objects = $repository->load($criteria);
            
            foreach ($objects as $object) {
                $pacient_name = $object->forwarding->attendance->pacient->pacient_name;
                $group_name = SystemGroup::find(TSession::getValue('usergroupids'))->name;
                $obj  = (object) [ // array do propover
                    'ocupation' => $group_name,
                    'title'=> $object->system_user->name,
                    'person' => $pacient_name,
                    'description' => Convert::toDate($object->start_time,'H:i')
                ];
                $this->fc->addEvent($object->id, $pacient_name, $object->start_time, $object->end_time, null, $object->hexacolor, $obj);
            }
            
            TTransaction::close();
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
