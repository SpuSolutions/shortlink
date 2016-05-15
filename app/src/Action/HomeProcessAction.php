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
        $expireTime = $request->getParam('expireTime');
        $password = $request->getParam('password');

        $array_content = array("url" => $url, "expireTime" => $expireTime, "password" => $password);

        $encryptionMethod = "AES-128-CFB";  // AES is used by the U.S. gov't to encrypt top secret documents.


        if (empty($url) || filter_var($url, FILTER_VALIDATE_URL) === false) {
            $viewData['errors'][] = "The URL is not valid. (Example: http://www.example.com)";
        }

        if (!ctype_alnum($word) || empty($word) || in_array($word, array('new', 'about', '404'))) {
            $viewData['errors'][] = "Memorable word is not valid. Must contain only alphanumeric characters.";
        } else {
            $word = preg_replace("/[^a-zA-Z0-9]+/", "", $word);

            if ($password !== "") {
                $password = "yes";
            }
            $filePath = INC_ROOT . '/shortlink/uploads/' . md5($word);
        }

        if (empty($viewData['errors'])) {

            if ($filePath !== null && (!file_exists($filePath) || (file_exists($filePath) && ((time() - json_decode(utf8_decode(file_get_contents($filePath)))->expireTime* 60)  < filemtime($filePath))))) {


                $this->logger->info("Creating file: " . $filePath . " and url: " . $url . " and expireTime: ") ;

                if ($password !== "") {
                    //we cannot use bytes -> random_bytes
                    //$iv = random_bytes(16)
                    //delete the warning
                    $encrypted =  @openssl_encrypt($array_content["url"], $encryptionMethod, $password);
                    $this->logger->info("crypted content : " . $encrypted);
                    $array_content["url"] = $encrypted;

                }

                file_put_contents($filePath, json_encode($array_content));

                $results = json_decode(utf8_decode(file_get_contents($filePath)));
                $this->logger->info("test results: ". $results->expireTime);


            } else {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $viewData['errors'][] = "Memorable word already taken.";
            }
        }


        if (empty($viewData['errors'])) {
            $router = $this->router;
            $this->logger->info("Created: word: $word, url: $url, expireTime: $expireTime, filePath: $filePath");
            return $response->withRedirect($router->pathFor('detail', ['id' => $word]));
        } else {
            $viewData['url'] = $url;
            $viewData['word'] = $word;
            $viewData['expireTime'] = $expireTime;
            $viewData['pageTitle'] = "Homepage";
            $this->logger->info("Create form page action dispatched");
            $this->view->render($response, 'home.twig', $viewData);
            return $response;
        }

    }
}
