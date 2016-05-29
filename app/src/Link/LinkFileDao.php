<?php

namespace App\Link;

use App\Link\Link;
use App\Link\LinkFactory;
use App\Security\Encryption;

Class LinkFileDao implements LinkDaoInterface
{

    private $settings;
    private $linkFactory;

    /**
     *
     * @param LinkFactory $linkFactory
     * @param array $settings
     */
    public function __construct(LinkFactory $linkFactory, Array $settings)
    {
        $this->linkFactory = $linkFactory;
        $this->settings = $settings;
    }

    /**
     * Save a link to the filesystem
     *
     * @param Link $link
     * @return Link|boolean
     */
    public function create(Link $link)
    {
        // If we have reached the maximum number of files allowed in the storage return false
        if ($this->getNumberOfFiles() > $this->settings['totalLinkFiles']) {

            return false;
        }

        $word = $link->getWord();
        $filePath = $this->buildFilePath($word);

        // Only proceed to create the file if a file with the same name doesn't exist or exists and has expired
        $testExistingLink = $this->getByWord($word);
        if ($testExistingLink === false || ($testExistingLink && $testExistingLink->hasExpired())) {

            // Proceed to create the file
            // If file creation fails return false
            if (!$this->saveFileContent($filePath, $link)) {
                return false;
            } else {
                return $link;
            }

        } else {
            return false;
        }
    }

    /**
     * Search for a link in the filesystem
     *
     * @param type $word
     * @return boolean
     */
    public function getByWord($word)
    {

        $filePath = $this->buildFilePath($word);
        if (file_exists($filePath)) {

            $fileData = $this->getFileContent($filePath);

            // Build the link object
            $link = $this->linkFactory->create();
            $url = $fileData->url;


            if ($fileData->passwordProtected == true) {
                //we need encryption
                $password = 'password';
                //TODO: same level than encryption
                $link->setPasswordProtected($password);
                $encryptClass = new Encryption($password);
                $url = $encryptClass->decrypt($url, $password);

            }
            $link->setWord($word);
            $link->setUrl($fileData->url);
            $link->setExpireTime($fileData->expireTime);
            $link->setCreated($fileData->created);
            $link->setPasswordProtected($fileData->passwordProtected);

            return $link;

        } else {
            return false;
        }
    }

    /**
     * Build the file path for a $word
     *
     * @param type $word
     * @return type
     */
    private function buildFilePath($word)
    {
        return $this->settings['upload_path'] . $word;
    }

    /**
     * Return a std object of the contents of a file
     *
     * @param type $filePath
     * @return type
     */
    private function getFileContent($filePath)
    {
        $data = file_get_contents($filePath);
        $formattedData = json_decode(base64_decode($data));
        return $formattedData;
    }

    /**
     *
     * @param type $filePath
     * @param type $link
     * @return type
     */
    private function saveFileContent($filePath, $link)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return file_put_contents($filePath, $link);
    }

    /**
     * Returns the number of Link files in the storage directory
     */
    private function getNumberOfFiles()
    {
        $fi = new \FilesystemIterator($this->settings['upload_path'], \FilesystemIterator::SKIP_DOTS);
        $count = iterator_count($fi) - 2; // -2 because we have both .keepme and .htaccess files to ignore in the count
        return $count;
    }
}