<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller to display default pages.
 *
 * Provides a route for default pages
 *
 * @author  Siaka MANSALY <siaka.mansaly@gmail.com>
 *
 * @package: App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * Display Homepage of application ToDo & Co.
     *
     * This is the homepage of the application.
     * Only the user who is logged in can see this page.
     *
     * @Route("/", name="homepage")
     */
    public function indexAction(): Response
    {
        return $this->render('default/index.html.twig');
    }
}
