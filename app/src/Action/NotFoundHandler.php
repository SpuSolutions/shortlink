<?php
namespace App\Action;

use Slim\Handlers\NotFound;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class NotFoundHandler extends NotFound {

    private $view;
    private $templateFile;

    public function __construct(Twig $view, $templateFile) {
        $this->view = $view;
        $this->templateFile = $templateFile;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response) {
        parent::__invoke($request, $response);

        $viewData = array('pageTitle' => 404);
        $this->view->render($response, $this->templateFile, $viewData);

        return $response->withStatus(404);
    }

}