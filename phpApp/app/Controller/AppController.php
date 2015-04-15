<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    public $components = array(
        'DebugKit.Toolbar',
        'Session',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'account',
                'action' => 'login'
            ),
            'loginRedirect' => array(
                'controller' => 'Index',
                'action' => 'index'
            ),
            'logoutRedirect' => array(
                'controller' => 'account',
                'action' => 'login'
            ),
            'authorize' => array('Controller'),
            'authenticate' => array('Isabel'),
            'authError' => 'No puede acceder a esta página o realizar esa acción debido a falta de permisos. Debe hablar con un Administrador para realizar esta operación.',
            'sessionKey' => false
        ),
    );

    public function beforeFilter() {
        $this->layout = 'private-layout';
        $this->set('activeOption', $this->request->action);
    }

    public function isAuthorized($usuario) {
        return true;
    }

    protected function _checkDatabase($url, $method = 'POST') {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        switch ($method) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case 'GET':
            default:
                break;
        }

        $data = curl_exec($ch);
        $headers = curl_getinfo($ch);

        curl_close($ch);

        $status = $headers['http_code'];
        if ($status == '200'){
            return true;
        }
        return false;
    }
}
