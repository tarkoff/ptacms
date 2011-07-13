<?php
/**
 * User Edit Form
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

class Default_Form_Users_Acl extends KIT_Form_Abstract
{
	/**
	 * @var KIT_Default_User
	 */
	private $_user;

	public function __construct($id = 0, $options = null)
	{
		$id = intval($id);
		$this->_user = new KIT_Default_User();
		$this->_user->loadById($id);

		$userAclTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_User_Acl');
		$userGroupTable = $this->_user->getDbTable();

		parent::__construct($options);
		$this->setName('user_aclForm');
		$this->setLegend('User Acl Form');

		$groupId = new Zend_Form_Element_Hidden('id');

		$deniedOptions = array();
		foreach ($userAclTable->getDeniedResources($id) as $resource) {
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
		foreach ($userAclTable->getUserResources($id, $this->_user->getGroupId()) as $resource) {
			$allowedOptions[$resource['RESOURCES_ID']] = $resource['RESOURCES_TITLE'];
		}
		$allowedRes = new Zend_Form_Element_Select('allowedRes');
		$allowedRes->setLabel('Allowed Resource')->setRequired(false);
		$allowedRes->addMultiOptions($allowedOptions);
		$this->addElement($allowedRes);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'user_aclForm_submit');
		$submit->setLabel('Save');
		$this->addElement($submit);
	}

	public function submit()
	{
		if ($this->isPost()) {
			$formData = (array)$this->getPost();
			$userAclTable = KIT_Db_Table_Abstract::get('KIT_Default_DbTable_User_Acl');
			if ($userAclTable->setUserRights(
					$this->_user->getId(),
					$formData['allowedRes'],
					$this->_user->getGroupId()
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
