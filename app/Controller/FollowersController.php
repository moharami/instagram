<?php
App::uses('AppController', 'Controller');
/**
 * Followers Controller
 *
 * @property Follower $Follower
 * @property PaginatorComponent $Paginator
 */
class FollowersController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

/**
 * index method
 *
 * @return void
 */
	public function index() {
		Configure::write('debug',0);
		$list = $this->Follower->Field->list_alias();
	    foreach ($list as $key => $item) {
	    	$conditions = array(
	    		'conditions'  => array(
	    			'field_id' => $key,
	    		),	    		
	    		'order'	  => array(
	    			'created desc',
	    		),
	    		'limit'=> 13,
	    	);
	    	$result[$key] = $this->Follower->find('all',$conditions);	    	
		}
		$this->set(compact('result'));		
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Follower->exists($id)) {
			throw new NotFoundException(__('Invalid follower'));
		}
		$options = array('conditions' => array('Follower.' . $this->Follower->primaryKey => $id));
		$this->set('follower', $this->Follower->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Follower->create();
			if ($this->Follower->save($this->request->data)) {
				$this->Session->setFlash(__('The follower has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The follower could not be saved. Please, try again.'));
			}
		}
		$fields = $this->Follower->Field->find('list');
		$this->set(compact('fields'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->Follower->exists($id)) {
			throw new NotFoundException(__('Invalid follower'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Follower->save($this->request->data)) {
				$this->Session->setFlash(__('The follower has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The follower could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Follower.' . $this->Follower->primaryKey => $id));
			$this->request->data = $this->Follower->find('first', $options);
		}
		$fields = $this->Follower->Field->find('list');
		$this->set(compact('fields'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->Follower->id = $id;
		if (!$this->Follower->exists()) {
			throw new NotFoundException(__('Invalid follower'));
		}
		$this->request->allowMethod('post', 'delete');
		if ($this->Follower->delete()) {
			$this->Session->setFlash(__('The follower has been deleted.'));
		} else {
			$this->Session->setFlash(__('The follower could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
