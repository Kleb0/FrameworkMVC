<?php

class Articles extends AbstractController {
    public function index(){
        $data = [
            'title' => 'Articles page',
        ];
        $this->render('index', $data);
    }
}