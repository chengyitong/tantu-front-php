<?php
class sysaccountModel extends RelationModel{
	protected $_link = array(
		array(
			'mapping_type'=>BELONGS_TO,
			'class_name'=>'syscode',
			'mapping_name'=>'syscode',
			'foreign_key'=>'sysaccount_permissionid',
			'mapping_fields'=>'syscode_name',
		)
    );
}