<?php 

namespace Controllers;

use MVC\Router;
use Model\Admin;

class LoginController {

    public static function crear() {        
        $email = 'pedri@gmail.com';
        $password = 'fran789';

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'email' => $email,
            'password' => $passwordHash
        ];

        $user = new Admin($data);

        $resultado = $user->guardar();

    }
    
    public static function login( Router $router) {

        $errores = [];



        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Admin($_POST);
            $errores = $auth->validar();

            echo '<pre>';
            var_dump($auth);
            echo '</pre>';
        
            if(empty($errores)) {

                $resultado = $auth->existeUsuario();
                
                if( !$resultado ) {
                    $errores = Admin::getErrores();
                } else {

                    $auth->comprobarPassword($resultado);

                    if($auth->autenticado) {
                       $auth->autenticar();
                    } else {
                        $errores =Admin::getErrores();
                    }
                }
            }
        }

        $router->render('auth/login', [
            'errores' => $errores
        ]); 
    }

    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }
}