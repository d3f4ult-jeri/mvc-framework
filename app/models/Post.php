<?php
/**
 * Created by PhpStorm.
 * User: Jerico Tilacas
 * Date: 2/3/2020
 * Time: 11:58 AM
 */

  class Post
  {
      private $db;

      public function __construct()
      {
          $this->db = new Database;
      }

      public function getPosts()
      {
          $this->db->query("SELECT * FROM posts");

          return $this->db->resultSet();
      }
  }