<?php
/**
 * Created by PhpStorm.
 * User: Jerico Tilacas
 * Date: 1/31/2020
 * Time: 10:32 AM
 */

  class Pages extends Controller
  {
      public function __construct()
      {
          $this->postModel = $this->model('Post');
      }

      public function index()
      {
          $posts = $this->postModel->getPosts();

          $data = [
              'title' => 'Welcome',
              'posts' => $posts
          ];

          $this->view('pages/index', $data);
      }

      public function about()
      {
          $data = [
              'title' => 'About Us'
          ];

          $this->view('pages/about', $data);
      }
  }