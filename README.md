# Model-View-Controller

A simple and easy to understand MVC skeleton application. Perfect for quickly building real and clean applications. These supports:

- Routing
- Controller
- View
- Model


## URL Routing

By default, a URL has a one-to-one relationship to the Controller and Method called which has the following format: 

 `http://jframework.com/controller/method/param1/param2`
 
 In some instances, however, you may want to remap this relationship so that a different class/method can be called instead of the one corresponding to the URL. For example, let’s say you want your URLs to have this prototype:
 
  ```
  jframework.com/pages/1/
  jframework.com/pages/2/
  jframework.com/pages/3/
  jframework.com/pages/4/
  ```
**NOTE**: it is not a requirement that you pass all parameters in the URL. You can create URL routes having only a controller/method/ pattern and provide data via HTTP POST, for example, coming from a form.

You can add your custom routes to the routes configuration file located here: /app/config/routes.php

## Models

Models that you create must be stored in the /app/models/ directory and MUST use a Post.php file naming format.

  ``` php
<?php

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
  ```
  
## PDO Database Class

This class shows how to connect to database using PDO and creating prepared statements, bind values and returning rows and results. 
 
  ``` php
<?php

class Database
{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh; // DB Handler
    private $stmt;
    private $error;

    public function __construct()
    {
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO:: ATTR_PERSISTENT => true,
            PDO:: ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Create PDO Instance
        try
        {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        catch (PDOException $e)
        {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    // Prepare statement with query
    public function query($sql)
    {
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null)
    {
        if(is_null($type))
        {
            switch(true)
            {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute()
    {
        return $this->stmt->execute();
    }

    // Get result set as array of objects
    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single result as object
    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Get the row count
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
}
  ```

## Views

Views are the presentation part of the MVC pattern and as such they define design/layout of the page. A typical View might look like the following:

Sometimes you may have reusable parts of your page such as a header and footer. The View Helper loads by default and allows you to "require" your View. In this example, we are adding the include header and footer View fragments by specifying their location in the sub-directory called "include" within the Views directory, located here: /app/views/include/

  ``` php
<?php require APP_ROOT . '/views/include/header.php'; ?>
  <h1><?php echo $data['title']; ?></h1>
    <ul>
        <?php foreach ($data['posts'] as $post): ?>
            <li><?php echo $post->title; ?></li>
        <?php endforeach; ?>
    </ul>
<?php  require APP_ROOT .  '/views/include/footer.php'; ?>
  ```
  
## Controllers

To understand how Controllers work we need to back up a little bit and recall how we format a URL. For this example lets say we need to query information about a user and display the information on a report.

 `http://jframework.com/pages/about/param1/param2`
 
 Our Controller might look like the following:
 
   ``` php
   <?php
   
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

   ```

## Base Controller

Loads the model and views file

 
   ``` php
   
   <?php
   /**
    * Base Controller
    * Loads the models and views
    */
   
     class Controller
     {
        // Load model
         public function model($model)
         {
             // Require model file
             require_once '../app/models/' . $model . '.php';
   
             // Instantiate model
             return new $model();
         }
   
         // Load view
         public function view($view, $data = [])
         {
             // Check for view file
             if(file_exists('../app/views/' . $view  . '.php'))
             {
                 require_once '../app/views/' . $view . '.php';
             }
             else
             {
                 // View does not exist
                 die('View does not exists');
             }
         }
     }

   ```
