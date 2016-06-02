<?php
namespace App\Action;

use Slim\Views\Twig;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Link\LinkService;
use App\ValidatorInterface;

final class HomeProcessAction
{
    private $view;
    private $logger;
    private $router;
    private $linkService;
    private $linkValidator;

    public function __construct(Twig $view, LoggerInterface $logger, $router, LinkService $linkService, ValidatorInterface $linkValidator)
    {
        $this->view = $view;
        $this->logger = $logger;
        $this->router = $router;
        $this->linkService = $linkService;
        $this->linkValidator = $linkValidator;
    }

    public function __invoke(Request $request, Response $response, $args)
    {
        $viewData = array();

        $linkData = new \stdClass();
        $linkData->word = $request->getParam('word');
        $linkData->url = $request->getParam('url');
        $linkData->expireTime = (int)$request->getParam('expireTime');
        $linkData->password = $request->getParam('password');
        $linkData->passwordConfirm = $request->getParam('passwordConfirm');

        //  If the user forgets to add http:// to the beginning of the url, then add it in ourselves
        $parts = parse_url($linkData->url);
        if($parts){
            if(!isset($parts["scheme"])){
                $linkData->url = "http://$linkData->url";
            }
        }

        // Check if input link data is valid
        if ($this->linkValidator->isValid($linkData)) {

            // Send data to the Link Service
            if ($linkData->password != '') {
                $this->logger->info("ecco cosa è password: " . $linkData->password);
            }
            $link = $this->linkService->create($linkData->word, $linkData);

            if ($link !== false) {
                $router = $this->router;
                return $response->withRedirect($router->pathFor('detail', ['id' => $linkData->word]));
            } else {
                $viewData['url'] = $linkData->url;
                $viewData['word'] = $linkData->word;
                $viewData['expireTime'] = $linkData->expireTime;
                $viewData['pageTitle'] = "Homepage";
                $viewData['errors'][] = "Memorable word already taken.";
                $this->logger->info("Create form page action dispatched");
                $this->view->render($response, 'home.twig', $viewData);
                return $response;
            }

        } else {
            $viewData['url'] = $linkData->url;
            $viewData['word'] = $linkData->word;
            $viewData['expireTime'] = $linkData->expireTime;
            $viewData['pageTitle'] = "Homepage";
            $viewData['errors'] = $this->linkValidator->getErrors();
            $this->logger->info("Create form page action dispatched");
            $this->view->render($response, 'home.twig', $viewData);
            return $response;
        }

    }
}
