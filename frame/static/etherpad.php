<?php

function etherpadApiRequest($method, $params) {
	$params['apikey'] = 'f81b363aa86b097155772d6b691c0392fc058b95670db0bc558bba3da9992f42';
	$res = json_decode(file_get_contents(
			"http://etherpad:8000/api/1/$method?" . http_build_query($params)));
	if ($res->code != 0) {
		echo 'ERROR';
		var_dump($res);
		return;
	}
	return $res->data;
}

function fetchEtherpadAuthorId() {
	return etherpadApiRequest('createAuthorIfNotExistsFor', array(
		'name' => $user,
		'authorMapper' => $user
	))->authorID;
}

function createGroup() {
	return etherpadApiRequest('createGroup')->groupId;
}

function create() {
	
}

function listPads() {
	
}

//$user = $_SERVER['HTTP_REMOTE_USER'];
$user = "hendrik.raetz";

if (!isset($_SESSION['etherpad_author_id']))
	$_SESSION['etherpad_author_id'] = fetchEtherpadAuthorId();

if (isset($_POST['title'])) {
	// attempt to create pad
	// TODO redirect
	header("Location: ". $newUrl);
	die();
}

echo $_SESSION['etherpad_author_id'];

$groupId = etherpadApiRequest("createGroup", array())->groupID;
// echo "<br>";
// var_dump($groupId);
// echo "<br>";
var_dump(etherpadApiRequest("createGroupPad", array("groupID" => $groupId, "padName" => "405")));
echo "CREATEING SESSION FOR $groupId AND ".$_SESSION['etherpad_author_id'].'<br>';
$sessionID = etherpadApiRequest("createSession", array("groupID" => $groupId, "authorID" => $_SESSION['etherpad_author_id'], "validUntil" => time() + 1000000000))->sessionID;
echo "<br>";
echo "<br>";

setcookie("sessionID", $sessionID, 0, '/');

$pads = array();
//$list = etherpadApiRequest("listPadsOfAuthor",
	//array('authorID' => $_SESSION['etherpad_author_id']))->padIDs;
$list = etherpadApiRequest("listPads", array('groupID' => $groupId))->padIDs;
var_dump($list);
echo "<br>";
foreach ($list as $id) {
	preg_match("/\\$(.+$)/", $id, $matches);
	echo "MATCHES:";
	var_dump($matches);
	array_push($pads, array(
		link => $matches[1],
		name => $matches[1]
	));
}

var_dump($pads);
?>

<div class="container">
	<div class="col-md-8">
		<ul>
		<?php foreach ($pads as $pad) { ?>
			<li><a href="<?= $pad["link"] ?>"><?= $pad["name"] ?></a></li>
		<?php } ?>
		</ul>
	</div>
	<div class="col-md-4">
		<form method="post" action="/static/etherpad.php">
			<div class="form-group">
				<label class="control-label">Titel</label>
				<input class="form-control" name="title">
			</div>
			<input type="submit" class="btn btn-primary" value="Pad erstellen">
		</form>
	</div>

	<iframe src="http://localhost/embed/etherpad/p/<?= $list[0] ?>">
</div>
