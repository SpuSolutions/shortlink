<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeProcessAction
{
    private $view;
    private $logger;

    public function __construct(Twig $view, LoggerInterface $logger, $router)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->router = $router;
    }

    public function __invoke(Request $request, Response $response, $args)
    {        
        $viewData = array();
        $filePath = null;

        $word = $request->getParam('word');
        $url = $request->getParam('url');        

        if (empty($url) || filter_var($url, FILTER_VALIDATE_URL) === false) {
            $viewData['errors'][] = "The URL is not valid. (Example: http://www.example.com)";
        }

        if(!ctype_alnum($word) || empty($word) || in_array($word, array('new', 'about', '404'))){
            $viewData['errors'][] = "Memorable word is not valid. Must contain only alphanumeric characters.";
        } else {
            $word = preg_replace("/[^a-zA-Z0-9]+/", "", $word);
            $filePath = INC_ROOT . '/shortlink/uploads/'.md5($word);
        }

        if(empty($viewData['errors'])){

            $interval= (time() - intval(fgets(fopen($filePath, 'r')))) * 60 ; //in minuti
            if($filePath !== null && (!file_exists($filePath) || (file_exists($filePath) && ($interval > filemtime($filePath)))))
            {
                $this->logger->info("Create file: ".$filePath." and url: ".$url);
                file_put_contents($filePath, '50'.PHP_EOL);
                file_put_contents($filePath, $url, FILE_APPEND);

            } else {
                if(file_exists($filePath)){
                    unlink($filePath);
                }
                $viewData['errors'][] = "Memorable word already taken.";
            }
        }
        

        if(empty($viewData['errors'])){
            $router = $this->router;
            $this->logger->info("Created: word: $word, url: $url, filePath: $filePath");
            return $response->withRedirect($router->pathFor('detail', ['id' => $word]));
        } else {
            $viewData['url'] = $url;
            $viewData['word'] = $word;
            $viewData['pageTitle'] = "Homepage";
            $this->logger->info("Create form page action dispatched");
            $this->view->render($response, 'home.twig', $viewData);
            return $response;
        }        
        
    }
}
