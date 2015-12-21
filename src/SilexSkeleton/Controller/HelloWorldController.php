<?php
namespace SilexSkeleton\Controller;

use Symfony\Component\HttpFoundation\Request;

class HelloWorldController {
	
	private $twigEnv;
	
	public function __construct(\Twig_Environment $twigEnv) {
		$this->twigEnv = $twigEnv;
	}
	
	public function sayHello(Request $request){
		$username = $request->get("username");
		if(empty($username)) {
			$username = "developer";
		}
		return $this->twigEnv->render("index.html", array('username' => $username));
	}
	
}