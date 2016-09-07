<?php

namespace TL\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TL\UserBundle\Entity\User;
use TL\DashboardBundle\Entity\Folder;
use TL\DashboardBundle\Entity\Task;
use TL\DashboardBundle\Form\FolderType;
use TL\DashboardBundle\Form\TaskType;

//API stuff
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

//Serializer magic motherfucker
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class FolderController extends Controller
{
    public function indexAction()
    {   
        //Get the User object $user with all it's folders and associated tasks
        $user = $this->getDoctrine()->getManager()->getRepository('TLUserBundle:User')->UserFoldersWithTasks($this->getUser()->getId());
       
        foreach($user->getFolders() as $folder){
            foreach($folder->getTasks() as $task){
                if($task->getFinishedAt() !== null){
                    $endDate = $task->getFinishedAt();
                    $daysLeft = $endDate->diff(new \Datetime())->days;
                    $task->setDaysLeftToFinish($daysLeft);
                } else {
                    $task->setDaysLeftToFinish(null);
                }
            }
        }

        return $this->render('TLDashboardBundle:Folder:index.html.twig', array(
            'user'   => $user
        ));
    }


    public function addFolderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $folder = new Folder();

        $folderForm = $this->get('form.factory')->create(FolderType::class, $folder);

        if($request->isMethod('POST') && $folderForm->handleRequest($request)->isValid()){

            $user = $this->getUser();

            $user->addFolder($folder);
            
            $this->get('fos_user.user_manager')->updateUser($user);

            $request->getSession()->getFlashBag()->add('info', 'Your folder '. $folder->getTitle() .' had been successfuly added.');

            return $this->redirectToRoute('tl_dashboard_homepage');
        }
        return $this->render('TLDashboardBundle:Folder:folderForm.html.twig', array(
            'folderForm' => $folderForm->createView()
        ));
    }


    public function editFolderAction(Request $request, $folderId)
    {
        $em = $this->getDoctrine()->getManager();

        $folder = $em->getRepository('TLDashboardBundle:Folder')->findOneById($folderId);

        $folderForm = $this->get('form.factory')->create(FolderType::class, $folder);

        if($request->isMethod('POST') && $folderForm->handleRequest($request)->isValid()){

            $user = $this->getUser();

            $user->addFolder($folder);

            $this->get('fos_user.user_manager')->updateUser($user);

            $request->getSession()->getFlashBag()->add('info', 'Your folder '. $folder->getTitle() .' had been successfuly added.');

            return $this->redirectToRoute('tl_dashboard_homepage');
        }
        return $this->render('TLDashboardBundle:Folder:folderForm.html.twig', array(
            'folderId'  => $folderId,
            'folderForm' => $folderForm->createView()
        ));
    }

    public function deleteFolderAction(Request $request, $folderId)
    {
        $em = $this->getDoctrine()->getManager();

        $folder = $em->getRepository('TLDashboardBundle:Folder')->find($folderId);

        $em->remove($folder);

        $em->flush();

        $request->getSession()->getFlashBag()->add('delete', 'Your folder '. $folder->getTitle() .' had been successfuly deleted.');

        return $this->redirectToRoute('tl_dashboard_homepage');
    }

    public function addTaskAction(Request $request, $folderId)
    {
        $em = $this->getDoctrine()->getManager();
        
        $folder = $em->getRepository('TLDashboardBundle:Folder')->find($folderId);

        $task = new Task();

        $taskForm = $this->get('form.factory')->create(TaskType::class, $task);

        if($request->isMethod('POST') && $taskForm->handleRequest($request)->isValid()){

            $folder->addTask($task);

            $em->persist($folder);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Task successfully added in '.$folder->getTitle().' Folder');

            return $this->redirectToRoute('tl_dashboard_homepage');
        }

        return $this->render('TLDashboardBundle:Task:taskForm.html.twig', array(
            'taskForm'      => $taskForm->createView(),
            'folderTitle'   => $folder->getTitle()
        ));
    }

    public function deleteTaskAction(Request $request, $taskId)
    {
        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository('TLDashboardBundle:Task')->find($taskId);

        $em->remove($task);
        $em->flush();

        $request->getSession()->getFlashBag()->add('delete', 'Your task had been successfuly been deleted.');

        return $this->redirectToRoute('tl_dashboard_homepage');
    }

    public function setTaskDoneAction(Request $request, $taskId)
    {
        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository('TLDashboardBundle:Task')->find($taskId);

        $task->setStatus(true);

        $task->getFolder()->increaseNbTaskDone();

        $task->getFolder()->decreaseNbTaskPending();

        $task->setFinishedAt(new \DateTime('now'));

        if($task->getPriority() === true){

            $task->setPriority(false);

            $task->getFolder()->decreaseNbTaskPrioritary();
        }
        
        $em->persist($task);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'Well done man ! The task had been moved to your Done Folder.');

        return $this->redirectToRoute('tl_dashboard_homepage');
    }

    public function setTaskPriorityAction(Request $request, $taskId)
    {
        $em = $this->getDoctrine()->getManager();

        $task = $em->getRepository('TLDashboardBundle:Task')->find($taskId);

        if($task->getPriority() === true){

            $task->setPriority(false);

            $task->getFolder()->decreaseNbTaskPrioritary();

            $request->getSession()->getFlashBag()->add('info', 'Your task isn\'t a priority anymore.');
        } else {
            $task->setPriority(true);

            $task->getFolder()->increaseNbTaskPrioritary();

            $request->getSession()->getFlashBag()->add('info', 'Your task has now become a priority, now get to work dude !');
        }

        $em->persist($task);
        $em->flush();


        return $this->redirectToRoute('tl_dashboard_homepage');
    }

    public function priorityFolderAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('TLUserBundle:User')->FoldersWithTasksPrioritary($this->getUser()->getId());

        return $this->render('TLDashboardBundle:Folder:priorityFolder.html.twig', array(
            'user' => $user
        ));
    }

    public function doneFolderAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user =  $em->getRepository('TLUserBundle:User')->FoldersWithTasksDone($this->getUser()->getId());

        return $this->render('TLDashboardBundle:Folder:doneFolder.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/{userId}/folders", name="tl_dashboard_user_folders")
     * @Method("Get")
     */
    public function getUserFoldersAction($userId)
    {
          $user = $this->getDoctrine()->getManager()->getRepository('TLUserBundle:User')->UserFoldersWithTasks($userId);
       
        foreach($user->getFolders() as $folder){

            foreach($folder->getTasks() as $task){

                if($task->getFinishedAt() !== null){

                    $endDate = $task->getFinishedAt();

                    $daysLeft = $endDate->diff(new \Datetime())->days;

                    $task->setDaysLeftToFinish($daysLeft);

                } else {

                    $task->setDaysLeftToFinish(null);
                }
            }
        }

        $encoder = new JsonEncoder();

        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object){
             return $object;
        });

        $serializer = new Serializer(array($normalizer), array($encoder));

        $taskJsonData = $serializer->serialize($user, 'json');

        $response =  new Response();

        $response->setContent($taskJsonData);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}