<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class DetailAction
{
    private $view;
    private $logger;

    public function __construct(Twig $view, LoggerInterface $logger)
    {
        $this->view = $view;
        $this->logger = $logger;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $word = preg_replace("/[^a-zA-Z0-9]+/", "", $args['id']);
        $filePath = INC_ROOT . '/shortlink/uploads/'.md5($word);
        $viewData = array();

        if(!ctype_alnum($args['id'])){
            $this->view->render($response, '404.twig', ["message" => "The link you were looking for does not exist or may have expired."]);
            $this->logger->error("The link you were looking for does not exist: ". $args['id']);
            return $response->withStatus(404)->withHeader('Content-Type', 'text/html');
        }




        if(file_exists($filePath) && ((time() - intval(fgets(fopen($filePath, 'r')))* 60)  < filemtime($filePath))){
            $lines = file($filePath);//file in to an array
            //lines is an array
            $url = $lines[1];
            $expireTime = $lines[0];

            if((time() - intval(fgets(fopen($filePath, 'r')))) * 60 < filemtime($filePath)) {
                $this->logger->info("Detail page action dispatched ".fgets(fopen($filePath, 'r')));
            }

            $viewData['word'] = $word;
            $viewData['url'] = htmlspecialchars($url);
            $viewData['created'] = date("Y-m-d H:i:s", filemtime($filePath));
            $viewData['expireDate'] = date("Y-m-d H:i:s", filemtime($filePath) + $expireTime*60);
            $viewData['expiresIn'] = ceil((filemtime($filePath) + $expireTime*60 - time()) / 60)." minutes";
            $viewData['pageTitle'] = "Detail";
            $this->view->render($response, 'detail.twig', $viewData);
            return $response;
        } else {
            $this->logger->error("The link you were looking for does not exist: ".file_exists($filePath).
                " expire Time: ".intval(fgets(fopen($filePath, 'r'))). " time now ". time(). "tempo creazione: ".filemtime($filePath));
            $this->logger->error(((time() - intval(fgets(fopen($filePath, 'r')))* 60)  < filemtime($filePath))?'true':'ala');

            if(file_exists($filePath)){
                unlink($filePath);
            }
            $this->view->render($response, '404.twig', ["message" => "The link you were looking for does not exist or may have expired."]);
            return $response->withStatus(404)->withHeader('Content-Type', 'text/html');
        }

        
    }
}
