<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Link\LinkService;

final class HomeProcessAction
{
    private $view;
    private $logger;
    private $router;
    private $linkService;

    public function __construct(Twig $view, LoggerInterface $logger, $router, LinkService $linkService)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->router = $router;
        $this->linkService = $linkService;
    }

    public function __invoke(Request $request, Response $response, $args)
    {        
        $viewData = array();

        $word = $request->getParam('word');
        $url = $request->getParam('url');
        $expireTime = (int)$request->getParam('expireTime');

        //	Chiedo perdono. Creare cosi un object e' un po' bruttino. Ma ho bisogno di un object velocemente.
        $data = (object)['url' => $url, 'expireTime' => $expireTime];

        //	Send data to the Link Service
        $link = $this->linkService->create($word, $data);        

        if($link !== false){
            $router = $this->router;
            return $response->withRedirect($router->pathFor('detail', ['id' => $word]));
        } else {
            $viewData['url'] = $url;
            $viewData['word'] = $word;
            $viewData['expireTime'] = $expireTime;
            $viewData['pageTitle'] = "Homepage";
            $viewData['errors'][] = "Memorable word already taken.";
            $this->logger->info("Create form page action dispatched");
            $this->view->render($response, 'home.twig', $viewData);
            return $response;
        }        
        
    }
}
