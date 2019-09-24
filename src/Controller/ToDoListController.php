<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository(Task::class)->findBy([],['id' => 'DESC']);

        return $this->render('index.html.twig', [
                'tasks' => $tasks
            ]);
    }

    /**
     * @Route("/create", name="create_task", methods={"POST"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $title = trim($request->request->get('title'));

        if (!$title) {
            return $this->redirectToRoute('home');
        }
        $task = new Task();
        $task->setTitle($title);

        $em->persist($task);

        $em->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/switch-status/{id}", name="switch_status")
     */
    public function switchStatus($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository(Task::class)->find($id);

        $task->setStatus(! $task->getStatus());
        $em->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/delete/{id}", name="delete_task")
     * @param $id
     * @return RedirectResponse
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository(Task::class)->find($id);
        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('home');
    }

}
