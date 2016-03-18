<?php
App::uses('Caption', 'Model');

/**
 * Caption Test Case
 *
 */
class CaptionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.caption',
		'app.user_media',
		'app.user',
		'app.comment',
		'app.count',
		'app.like',
		'app.image',
		'app.standard_resolution',
		'app.low_resolution',
		'app.thumbnail'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Caption = ClassRegistry::init('Caption');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Caption);

		parent::tearDown();
	}

}
