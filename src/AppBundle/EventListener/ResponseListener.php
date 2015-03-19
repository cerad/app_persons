<?php
namespace Cerad\Bundle\AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ResponseListener implements EventSubscriberInterface
{
  public static function getSubscribedEvents()
  {
    return array
    (
      'kernel.response' => array(array('onKernelResponse', 10),)
    );
  }
  public function onKernelResponse(FilterResponseEvent $event)
  {
    // Allow cross domain queries
    $event->getResponse()->headers->set('Access-Control-Allow-Headers','Content-Type');
    $event->getResponse()->headers->set('Access-Control-Allow-Methods','GET, POST, PUT, DELETE, PATCH, OPTIONS');
    $event->getResponse()->headers->set('Access-Control-Allow-Origin', '*');
        
    // P3P Policy (IE Explorer and iframes I think)
    $event->getResponse()->headers->set('P3P', 
      'CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
  }
}
?>
