<?php
/**
 * User Groups Edit Form
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category   KIT
 * @package    KIT_Core
 * @copyright  Copyright (c) 2009-2010 KIT Studio
 * @license    New BSD License
 * @version    $Id: Acl.php 309 2010-04-19 21:06:53Z TPavuk $
 */

class Default_Form_Usergroups_Acl extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Default_UserGroup
	 */
	private $_userGroup;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_userGroup = new KIT_Default_UserGroup();
		$this->_userGroup->loadById($id);

		$groupAclTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_UserGroup_Acl');
		$userGroupTable = $this->_userGroup->getDbTable();

		parent::__construct($options);
		$this->setName('userGroup_aclForm');
		$this->setLegend('User Group Acl Form');

		$groupId = new Zend_Form_Element_Hidden('id');

		$deniedOptions = array();
		foreach ($groupAclTable->getDeniedResources($id) as $resource) {
			$deniedOptions[$resource['RESOURCES_ID']] = $resource['RESOURCES_TITLE'];
		}
		$deniedRes = new Zend_Form_Element_Select('deniedRes');
		$deniedRes->setLabel('Denied Resource')
				  ->setRequired(false)
				  ->addFilter('StripTags')
				  ->addFilter('StringTrim');
		$deniedRes->addMultiOptions($deniedOptions);

		$this->addElement($deniedRes);

		$allowedOptions = array();
		foreach ($groupAclTable->getGroupResources($id) as $resource) {
			$allowedOptions[$resource['RESOURCES_ID']] = $resource['RESOURCES_TITLE'];
		}
		$allowedRes = new Zend_Form_Element_Select('allowedRes');
		$allowedRes->setLabel('Allowed Resource')->setRequired(false);
		$allowedRes->addMultiOptions($allowedOptions);
		$this->addElement($allowedRes);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'userGroup_aclForm_submit');
		$submit->setLabel('Save');
		$this->addElement($submit);
	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
			$groupAclTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_UserGroup_Acl');
			if ($groupAclTable->setGroupRights(
					$this->_userGroup->getId(),
					$formData['allowedRes']
				)
			) {
				return true;
			} else {
				$this->populate($formData);
			}
		}
		return false;
	}
}
