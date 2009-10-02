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

//require_once PTA_PUBLIC_PATH . '/recaptcha/recaptchalib.php';

class Products_CommentsForm extends PTA_Control_Form 
{
	private $_productId;

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

		$captcha = new PTA_Control_Form_Text('captcha', 'Captcha', true);
		$captcha->setSortOrder(3);
		$this->addVisual($captcha);

		$submit = new PTA_Control_Form_Submit('submit', 'Search', true, 'Search');
		$submit->setSortOrder(4);
		$this->addVisual($submit);
		
//		$this->setVar('captcha', recaptcha_get_html(PTA_RECAPTCHA_PUBLIC_KEY));
		if (!$this->getVar('error')) {
			$this->setVar('error', self::FORM_ERROR_NONE);
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
			return self::FORM_ERROR_VALIDATE;
		}


		if (!$this->isValidCaptcha($data->captcha)) {
			return self::COMMENT_ERROR_CAPTCHA;
		}

		$post = PTA_DB_Object::get('Post');
		$post->loadFrom($data);
		$post->setProductId($this->_productId);

		if (!$post->save()) {
			return self::FORM_ERROR_SAVE;
		}

		return self::FORM_ERROR_NONE;
	}
	
	public function isValidCaptcha($captcha)
	{
		$captcha = strtolower($this->quote($captcha));

		foreach (explode(',', PTA_CAPTCHA_WRITE_UNSWER) as $unswer) {
			if (strtolower($unswer) == $captcha) {
				return true;
			}
		}

		return false;
	}
}
