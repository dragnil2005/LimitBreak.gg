<?php
$cfg['blowfish_secret'] = '70F61B59D40AE846F8DF9CEBE4C2E7FD';

$i = 0;
$i++;
$cfg['Servers'][$i]['auth_type'] = 'cookie';
$cfg['Servers'][$i]['host'] = '127.127.126.5'; // или 127.0.0.1 127.127.126.25
$cfg['Servers'][$i]['port'] = '3306';
$cfg['Servers'][$i]['compress'] = false;
$cfg['Servers'][$i]['AllowNoPassword'] = true;
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = ''; // если пустой
