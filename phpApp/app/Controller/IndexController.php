<?php
/**
 * The MIT License (MIT)
 * 
 * Copyright (c) 2015 
 * 
 * John Congote <jcongote@gmail.com>
 * Felipe Calad
 * Isabel Lozano
 * Juan Diego Perez
 * Joinner Ovalle
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');

class IndexController extends AppController {

    /*********************************************************************
     *              CONTROLLER DATA MEMBERS
     *********************************************************************/

    public $name = "Index";    //name of the controller. Usually this is just the plural form of the primary model the controller uses
    // public $uses = array('MiModelo', "Modelo2");        //Allows a controller to access additional models. name of the current controller’s model must also be included if uses is present
    // public $components = array("Component1", "Component2");     //make available packages of logic that are shared between controllers

    /*********************************************************************
     *              CONTROLLER CALLBACKS METHODS
     *********************************************************************/

    public function beforeFilter() {
        parent::beforeFilter();
    }

    // public function afterFilter() {
        
    // }

    // public function beforeRender() {

    // }

    /*********************************************************************
     *              CONTROLLER METHODS (ACTIONS)
     *********************************************************************/

    public function isAuthorized($usuario) {
        return true;
    }

    public function index() {

    }

    public function chats() {
        $retrieveUsersUrl = Configure::read(APPLICATION_ENV . '.retrieveUsersUrl');

        if (!$this->_checkDatabase($retrieveUsersUrl, 'POST')) {
            $this->Session->setFlash('Lo sentimos!!! El servicio para obtener las conversaciones previas no se encuentra disponible', $element = 'default', $params = array(), $key = 'warning');
        } else {
            $user = array('username' => $this->Auth->user('usuario'));

            $httpSocket = new HttpSocket();
            $httpResponse = $httpSocket->post($retrieveUsersUrl, $user);
            if ($httpResponse && $httpResponse->isOk()) {
                $jsonResponse = json_decode($httpResponse->body(), true);
                if (isset($jsonResponse['success']) && $jsonResponse['success'] == true) {
                    $this->set('users', $jsonResponse['users']);
                } else {
                    $this->Session->setFlash($jsonResponse['message'], $element = 'default', $params = array(), $key = 'warning');
                }
            } else {
                $this->Session->setFlash('Hay un problema en el servicio para obtener las conversaciones previas', $element = 'default', $params = array(), $key = 'warning');
            }
        }
    }

    public function viewChat($idUser, $nickname) {
        $this->set('idUser', $idUser);
        $this->set('nickname', $nickname);
    }

}