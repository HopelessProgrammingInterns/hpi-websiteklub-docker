<?php

function etherpadApiRequest($method, $params) {
	$params['apikey'] = 'abcdef123123';
	return json_decode(
		file_get_contents(
			"http://127.0.0.1/api/1/$method?" . http_build_query($params)))['data'];
}

function fetchEtherpadAuthorId() {
	return etherpadApiRequest('createAuthorIfNotExistsFor', array(
		'name' => $user,
		'authorMapper' => $user
	))['authorID'];
}

$user = $_SERVER['HTTP_REMOTE_USER'];

if (!$_SESSION['etherpad_author_id'])
	$_SESSION['etherpad_author_id'] = fetchEtherpadAuthorId();

