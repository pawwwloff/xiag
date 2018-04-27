<?php

return array(
		"Host" => "localhost",
		"Login" => "root",
		"Password" => "qwer1234",
		"Name" => "xiag",
		"Charset" => "utf8",
		"opt" => array(
				\PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
				\PDO::ATTR_EMULATE_PREPARES   => false,
		)
);
