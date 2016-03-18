<?php
App::uses('Relationship', 'Model');

/**
 * Relationship Test Case
 *
 */
class RelationshipTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.relationship',
		'app.relationship_type',
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
		'app.actor',
		'app.field'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Relationship = ClassRegistry::init('Relationship');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Relationship);

		parent::tearDown();
	}

}
