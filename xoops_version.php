<?php

#####################################################################
# --->     Mastop InfoDigital - Paixão por Informática              #
# --->                                                              #
# --->   Módulo Cadastros - Fernando Santos (aka topet05)           #
# --->            http://www.mastop.com.br                          #
# --->arquivo: xoops_version.php ultima alteração: 10/01/2004       #
#####################################################################

$modversion['name'] = 'Cadastros';
$modversion['version'] = 1.0;
$modversion['author'] = 'Mastop InfoDigital Ltda';
$modversion['description'] = 'Simples Sistema de Cadastros';
$modversion['credits'] = 'Fernando Santos (topet05)';
$modversion['license'] = 'Pode Passar';
$modversion['help'] = 'Não tem';
$modversion['official'] = 0;
$modversion['image'] = 'images/cadastros_logo.jpg';
$modversion['dirname'] = 'cadastros';

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

$modversion['tables'][0] = 'cadastros';

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

$modversion['hasMain'] = 1;

$modversion['templates'][1]['file'] = 'cadastros_index.html';
$modversion['templates'][1]['description'] = 'Página Visualizar Cadastros';
