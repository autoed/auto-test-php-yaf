<?php
use App\Models\Product;

class ProductController extends \Yaf\Controller_Abstract
{
    public function indexAction()
    {
    	//Eloquent ORM 调用方式
    	//$products = Product::all();
        echo "<h1></h1>";
        $request = $this->getRequest();
        var_dump($request);
        echo "<h1></h1>";
        $response = $this->getResponse();
        var_dump($response);
        echo "<h1></h1>";
        $products = ['name'=>'yaf'];
        $this->getView()->assign("products", $products);
    }
}