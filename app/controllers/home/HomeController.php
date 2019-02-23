<?php

namespace app\controllers\home;

use app\controllers\ContainerController;


class HomeController extends ContainerController{

	public function index()
	{
        $this->view([
            'title' => 'Sistema de Gerenciamento de Livros - SysLibrary'
        ], "home.index");
	}
}
