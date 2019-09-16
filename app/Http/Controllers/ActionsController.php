<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ActionsController extends Controller
{

    public function index()
    {
        $controllers = [];

        foreach (\Route::getRoutes()->getRoutes() as $route)
        {
            $action = $route->getAction();

            if (array_key_exists('controller', $action))
            {
                // You can also use explode('@', $action['controller']); here
                // to separate the class name from the method
                $controllers[] = $action['controller'];
            }
        }

        print_r($controllers);

//        $content= realpath(__DIR__ . "../../routes/api.php");
//
//        print_r(realpath(__DIR__ . "../../routes/api.php"));
//
//        preg_match_all("/^(.*Controller.*)$/mi", $content, $results);
//
//        $resourceroutes= $results[1];
//
//        print_r($resourceroutes);

//        $routes = Artisan::call('route:list', ['--path' => 'web']);
//
//        print_r($routes);

//        $routes = [];
//        foreach (\Route::getRoutes()->getIterator() as $route){
////            if (strpos($route->uri, 'Api') !== false){
////                $routes[] = $route->uri;
////            }
//
//            $routes[] = $route->uri;
//        }
//
//        print_r($routes);

//        $routes = Artisan::call('api:routes');
//        print_r($routes);
    }

}
