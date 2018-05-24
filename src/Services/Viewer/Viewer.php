<?php

namespace App\Services\Viewer;
use App\Services\Viewer\ViewerChecker;
use App\Services\Viewer\ViewerSetter;

class Viewer{
    private $viewerChecker;
    private $viewerSetter;

    public function __construct(ViewerChecker $viewerChecker, ViewerSetter $viewerSetter){
        $this->viewerChecker = $viewerChecker;
        $this->viewerSetter = $viewerSetter;
    }

    public function getViewer($blogpost, $ip){
        return $this->viewerChecker->getViewer($blogpost, $ip);
    }

    public function setViewer($blogpost, $ip){
        return $this->viewerSetter->setViewer($blogpost, $ip);
    }

    public function getsetViewer($blogpost, $ip){

        if($this->viewerChecker->getViewer($blogpost, $ip)){
            $this->viewerSetter->setViewer($blogpost, $ip);
        }

    }
}