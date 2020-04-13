<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAll();

        //$query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
            $products, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('index/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
