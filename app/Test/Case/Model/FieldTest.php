<?php
App::uses('Field', 'Model');

/**
 * Field Test Case
 *
 */
class FieldTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.field',
		'app.actor',
		'app.user',
		'app.comment',
		'app.user_media',
		'app.location',
		'app.image',
		'app.standard_resolution',
		'app.low_resolution',
		'app.thumbnail',
		'app.like',
		'app.count',
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
		$this->Field = ClassRegistry::init('Field');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Field);

		parent::tearDown();
	}

}
