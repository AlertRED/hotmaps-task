<?
require_once "client.php";

$token = auth('test', '12345');
$user = getUser('ivanov', $token);
$new_user = json_decode('{
    "active": "1",
	"blocked": true,
	"name": "Petr Petrovich",
	"permissions": [
    	{
        	"id": 1,
        	"permission": "comment"
    	},
 	]
}');
$user = updateUser('ivanov', $new_user, $token);
?>