<?php
App::uses('RelationshipType', 'Model');

/**
 * RelationshipType Test Case
 *
 */
class RelationshipTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.relationship_type',
		'app.relationship',
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
		$this->RelationshipType = ClassRegistry::init('RelationshipType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RelationshipType);

		parent::tearDown();
	}

}
