<?php

class ArticlesCuration extends AbstractController {
    public function index(){
        $data = [
            'title' => 'Curation des articles',
        ];
        $this->render('index', $data);
    }
}