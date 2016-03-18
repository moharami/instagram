<?php
/**
 * RelationshipFixture
 *
 */
class RelationshipFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'relationship_type_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'field_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'relation_time' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'description' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_persian_ci', 'charset' => 'utf8'),
		'result' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_persian_ci', 'charset' => 'utf8'),
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
			'relationship_type_id' => 1,
			'user_id' => 1,
			'field_id' => 1,
			'relation_time' => '2015-06-16 09:29:29',
			'description' => 'Lorem ipsum dolor sit amet',
			'result' => 'Lorem ipsum dolor sit amet'
		),
	);

}
