<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Link\LinkService;

final class DetailAction
{
    private $view;
    private $logger;
    private $linkService;

    public function __construct(Twig $view, LoggerInterface $logger, LinkService $linkService)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->linkService = $linkService;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $word = $args['id'];
        $viewData = array();
        $viewData['pageTitle'] = "Detail";

        $link = $this->linkService->getByWord($word);

        if($link !== false){

            //  A link has been found
            $viewData = array();
            $viewData['word'] = $link->getWord();
            $viewData['url'] = $link->getUrl();
            $viewData['expiresIn'] = $link->getRemainingMinutes()." minutes";
            $this->view->render($response, 'detail.twig', $viewData);
            
            return $response;


        } else {

            //  Link doesn't exist or has expired
            $this->view->render($response, '404.twig', ["message" => "The link you were looking for does not exist or may have expired."]);
            $this->logger->error("The link you were looking for does not exist: ". $args['id']);
            return $response->withStatus(404)->withHeader('Content-Type', 'text/html');
        }
        
    }
}
