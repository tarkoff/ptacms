<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Insert title here</title>
</head>
<body>
<form name="testForm" action="?" method="post">
	<input type="text" name="testText" />
	<input type="checkbox" name="testCheckbox" />
	<input type="submit" name="formSubmit" value="submited"/>
</form>
</body>
</html>

<?php
	print_r($_REQUEST);
?>
