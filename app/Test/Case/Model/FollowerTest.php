<?php
App::uses('Follower', 'Model');

/**
 * Follower Test Case
 *
 */
class FollowerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.follower',
		'app.field',
		'app.actor',
		'app.user',
		'app.user_media',
		'app.comment',
		'app.location',
		'app.image',
		'app.standard_resolution',
		'app.low_resolution',
		'app.thumbnail',
		'app.like',
		'app.relationship',
		'app.relationship_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Follower = ClassRegistry::init('Follower');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Follower);

		parent::tearDown();
	}

}
