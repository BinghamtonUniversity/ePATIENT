<?php

$uAppJSON = file_get_contents('/Users/tim/Desktop/ePAITNET_Admin.json');
$uApp = json_decode($uAppJSON,true);

var_dump(getcwd());
mkdir($uApp['app_id']);
mkdir($uApp['app_id'].'/scripts');
mkdir($uApp['app_id'].'/forms');
mkdir($uApp['app_id'].'/templates');

file_put_contents($uApp['app_id'].'/style.css',$uApp['code']['css']);

foreach($uApp['code']['forms'] as $index => $form) {
    file_put_contents($uApp['app_id'].'/forms/'.$form['name'].'.json',$form['content']);
}

foreach($uApp['code']['templates'] as $index => $form) {
    file_put_contents($uApp['app_id'].'/templates/'.$form['name'].'.mustache',$form['content']);
}

foreach($uApp['code']['scripts'] as $index => $form) {
    file_put_contents($uApp['app_id'].'/scripts/'.str_pad($index,2,"0",STR_PAD_LEFT).'_'.$form['name'].'.js',$form['content']);
}