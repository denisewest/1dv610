<?php

namespace view;


class LayoutView {

	public function echoPage(string $title, string $body)  {
		echo '<!DOCTYPE HTML SYSTEM>
<html>
  <head>
    <title>' . $title . '</title>
    <link rel="stylesheet" href="style.css">
    <meta http-equiv=\'content-type\' content=\'text/html; charset=utf8\'>
  </head>
  <body>
  	' . $body . '
  </body>
</html>';
	}
}