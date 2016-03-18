<?php
/**
 * FollowerFixture
 *
 */
class FollowerFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'field_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'follower' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'following' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'media' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'follower_diff' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'following_diff' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'hour' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'minute' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_persian_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'field_id' => 1,
			'follower' => 1,
			'following' => 1,
			'media' => 1,
			'created' => '2015-06-18 11:45:38',
			'modified' => '2015-06-18 11:45:38',
			'follower_diff' => 1,
			'following_diff' => 1,
			'hour' => 1,
			'minute' => 1
		),
	);

}
