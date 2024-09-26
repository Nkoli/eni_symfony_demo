<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cours', name: 'cours_')]
class CourseController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function index(CourseRepository $courseRepository): Response
    {
        $courses = $courseRepository->findAll();
        return $this->render('course/list.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Course $course, CourseRepository $courseRepository): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/add', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $course = new Course();
        $courseForm = $this->createForm(CourseType::class, $course);
//        dd($courseForm);

        // treat form
        $courseForm->handleRequest($request);

        // if the form is sent, add the data submitted to db
        if($courseForm->isSubmitted() && $courseForm->isValid()) {
            $em->persist($course);
            $em->flush();
            $this->addFlash('success', message: 'Course successfully added.');
            return $this->redirectToRoute('cours_show', ['id' => $course->getId()]);
        }


        return $this->render('course/create.html.twig', [
            'courseForm' => $courseForm,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit($course, Request $request, EntityManagerInterface $em): Response
    {
        $courseForm = $this->createForm(CourseType::class, $course);
        $courseForm->handleRequest($request);
        if($courseForm->isSubmitted() && $courseForm->isValid()) {
            // persisting isn't necessary bc Doctrine already knows the object
            $course->setDateModified(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', message: 'Course successfully edited.');
            return $this->redirectToRoute('cours_show', ['id' => $course->getId()]);
        }
        return $this->render('course/edit.html.twig');
    }

    #[Route('/demo', name: 'demo', methods: ['GET'])]
    public function demo(EntityManagerInterface $entityManager): Response
    {
//        Create an entity instance
        $course = new Course();

//        hydrater toutes les proprietes
        $course->setName('Symfony');
        $course->setContent('Server-side web development with Symfony');
        $course->setDuration(10);
        $course->setDateCreated(new \DateTimeImmutable());
        $course->setPublished(true);

        $entityManager->persist($course);

//        penser a flush sinon l'objet n'est pas enregistre dans la BD
        $entityManager->flush();

//        on modifie l'objet
        $course->setName('PHP');
//        on n'est pas oblige de faire le persist car doctrine connait deja l'objet
//        on sauvegard l'objet
        $entityManager->flush();

//        on supprime l'objet
        $entityManager->remove($course);
        $entityManager->flush();

        dump($course);

        return $this->render('course/create.html.twig');
    }


}
