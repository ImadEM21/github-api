<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(HttpClientInterface $HttpClient, CacheInterface $cache): Response
    {
        $response = $HttpClient->request('GET', 'https://api.github.com/users/Grafikart/repos', [
                'query' => [
                    'sort' => 'created'
                ] 
            ]);

        
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
            'repos' => $response->toArray()
        ]);
    }


        /**
     * @Route("/api/show/{id}", name="show")
     */
    public function show($id, HttpClientInterface $HttpClient): Response
    {
        $response = $HttpClient->request('GET', 'https://api.github.com/repositories/'.$id);


        if ($response->getStatusCode() === Response::HTTP_NOT_FOUND)
        {
            throw new NotFoundHttpException(sprintf('No repo with id %s', $id));
        }
        
        return $this->render('api/show.html.twig', [
            'repo' => $response->toArray(),
        ]);
    }


    /**
     * @Route("/api/{repoName}", name="commits")
     */
    public function commits($repoName, HttpClientInterface $HttpClient): Response
    {
        $response = $HttpClient->request('GET', 'https://api.github.com/repos/Grafikart/'. $repoName.'/commits');



        
        return $this->render('api/commits.html.twig', [
            'commits' => $response->toArray()
        ]);
    }
}
