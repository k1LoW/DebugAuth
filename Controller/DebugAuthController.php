<?php
App::uses('AppController', 'Controller');
class DebugAuthController extends AppController {

    public $components = array('Session',
                               'Security'
                               );
    public $helpers = array('Html',
                            'Form',
                            'Paginator');

    /**
     * beforeFilter
     *
     */
    public function beforeFilter(){
        if (Configure::read('debug') < 2) {
            throw new NotFoundException(__('Invalid Access'));
        }
        $this->Auth->allow('*');
        $this->modelClass = $this->Auth->authenticate['Form']['userModel'];
        App::uses($this->modelClass, 'Model');
        $this->{$this->modelClass} = ClassRegistry::init($this->modelClass);
    }

    /**
     * index
     *
     */
    public function index(){
        $this->{$this->modelClass}->recursive = 0;
        $this->set('modelName', $this->modelClass);
        $this->set('primaryKey', $this->{$this->modelClass}->primaryKey);
        $usernameField = empty($this->Auth->authenticate['Form']['fields']['username']) ? 'username' : $this->Auth->authenticate['Form']['fields']['username'];
        $passwordField = empty($this->Auth->authenticate['Form']['fields']['password']) ? 'password' : $this->Auth->authenticate['Form']['fields']['password'];
        $this->set('displayField', $this->{$this->modelClass}->displayField);
        $this->set('usernameField', $usernameField);
        $this->set('users', $this->paginate());
    }

    /**
     * login
     *
     */
    public function login($id = null){
        if (!$this->request->is('post')) {
            throw new OutOfBoundsException(__('Invalid Access'));
        }
        $query = array();
        $query['recursive'] = -1;
        $query['conditions'] = array($this->modelClass . '.' . $this->{$this->modelClass}->primaryKey => $id);
        $user = $this->{$this->modelClass}->find('first', $query);
        if (empty($user)) {
            throw new NotFoundException(__('Invalid Access'));
        }
        if ($this->Auth->login($user)) {
            $redirectUrl = preg_replace('/^\/debug_auth/', '', $this->Auth->redirect());
            $this->redirect($redirectUrl, null, true);
        } else {
            throw new OutOfBoundsException(__('Invalid Access'));
        }
    }
}