<?php

namespace Cerad\Bundle\PersonBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\DependencyInjection\ContainerInterface; 
        
class RefereeController extends Controller
{
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->refereeRepository = $container->get('cerad_person__referee__repository');
    }
    public function updateAction(Request $request, $id)
    {
        $refereeOld = $this->refereeRepository->find($id);
        
        $content = json_decode($request->getContent(),true);
        
        $refereeNew = array_merge($refereeOld,$content);
        
        $this->refereeRepository->updateReferee($refereeNew,$refereeOld);
        
        return new JsonResponse($refereeNew);
    }
    public function findAction($id)
    {
        $referee = $this->refereeRepository->find($id);
        
        return new JsonResponse($referee);
    }
    public function findByAction()
    {
        $referees = $this->refereeRepository->findAll();
        
        return new JsonResponse($referees);
        
    }
    /**
     * @Route("/referees", name="referees-insert")
     * @Method({"POST"})
     */
    public function insertAction(Request $request)
    {
        $referee1 = json_decode($request->getContent(),true);
        
        $referee2 = $this->refereeRepository->insert($referee1);
        
        return new JsonResponse($referee2);
    }
}
