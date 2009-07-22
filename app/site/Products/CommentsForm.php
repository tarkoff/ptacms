<?php
/**
 * Short description for file
 *
 * @package Core
 * @copyright  2008 P.T.A. Studio
 * @license    http://framework.zend.com/license   BSD License
 * @version    $Id: loginForm.php 62 2009-05-31 16:59:23Z TPavuk $
 * @author Taras Pavuk <tpavuk@gmail.com>
*/

require_once PTA_PUBLIC_PATH . '/recaptcha/recaptchalib.php';

class Products_CommentsForm extends PTA_Control_Form 
{
	private $_productId;

	const COMMENT_ERROR_NONE = 0;
	const COMMENT_ERROR_FIELDS = 1;
	const COMMENT_ERROR_CAPTCHA = 2;

	public function __construct($prefix, $productId)
	{
		parent::__construct($prefix);
		$this->_productId = $productId;
	}

	public function initForm()
	{
//		$this->setAction($this->getApp()->getBaseUrl() . '/Catalog/Search');

		
		
		$title = new PTA_Control_Form_Text('author', 'Name', true);
		$title->setSortOrder(1);
		$this->addVisual($title);

		$comment = new PTA_Control_Form_TextArea('post', 'Comment', true);
		$comment->setSortOrder(2);
		$this->addVisual($comment);

		$submit = new PTA_Control_Form_Submit('submit', 'Search', true, 'Search');
		$submit->setSortOrder(3);
		$this->addVisual($submit);
		
		$this->setVar('captcha', recaptcha_get_html(PTA_RECAPTCHA_PUBLIC_KEY));
		if (!$this->getVar('error')) {
			$this->setVar('error', self::COMMENT_ERROR_NONE);
		}
	}

	public function onLoad()
	{
		$data = new stdClass();
		$data->comment = '';
		return $data;
	}

	public function onSubmit(&$data)
	{
		$data->author = $this->quote($data->author);
		$data->post = $this->quote($data->post);

		if (empty($data->author) || empty($data->post)) {
			$this->setVar('error', self::COMMENT_ERROR_FIELDS);
			return false;
		}

		$resp = recaptcha_check_answer(
			PTA_RECAPTCHA_PRIVATE_KEY,
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]
		);

		if (!$resp->is_valid) {
			$this->setVar('error', self::COMMENT_ERROR_CAPTCHA);
			return false;
		}

		$post = PTA_DB_Object::get('Catalog_Post');
		$post->loadFrom($data);
		$post->setProductId($this->_productId);

		if ($post->save()) {
			$this->setVar('error', self::COMMENT_ERROR_NONE);
			return true;
		}
		return false;
	}
}
