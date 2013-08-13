<?php
/**
 * This file handles the retrieval and serving of categories
 */
class Categories_Controller
{
    /**
     * This template variable will hold the 'view' portion of the MVC for this 
     * controller
     */
    public $template = 'categories';
    private $method;

    function __construct($method) {
       $this->method = $method;
   }
    
    /**
     * @param array $getVars the GET variables posted to index.php
     */
    public function main(array $getVars)
    {
        $categoriesModel = new Categories_Model;
        $command = $getVars['command'];
        if ($this->method == "GET") {
          if ($_GET['values']){
            $categories = $categoriesModel->$command($_GET['values']);
           }
           else
            $categories = $categoriesModel->$command();
          }
        else if ($this->method == "POST")
          $categories="post";
    
        print_r($categories);
    }
}