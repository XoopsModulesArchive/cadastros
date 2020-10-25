<?php

#####################################################################
# --->     Mastop InfoDigital - Paixão por Informática              #
# --->                                                              #
# --->   Módulo Cadastros - Fernando Santos (aka topet05)           #
# --->            http://www.mastop.com.br                          #
# --->arquivo: index.php      ultima alteração: 10/01/2004          #
#####################################################################

require dirname(__DIR__, 3) . '/include/cp_header.php';

function cadastrosAdmin()
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    echo '<h4>' . _CAD_ADM_TITULO . "</h4>
	<form action='index.php' method='post' name='atualizaform'>
	<table width='100%' border='0' cellspacing='1' cellpadding='0' class='outer'><tr>
	<th align='center'>" . _CAD_ADM_NOME . "</th>
	<th align='center'>" . _CAD_ADM_ENDERECO . "</th>
	<th align='center'>" . _CAD_ADM_BAIRRO . "</th>
	<th align='center'>" . _CAD_ADM_CONTATO . "</th>
	<th width='3%' align='center'>" . _CAD_ADM_ATIVO . '</th>
 	<th>' . _CAD_ADM_OPCOES . '</th></tr>';

    $result = $xoopsDB->query('SELECT id, nome, endereco, bairro, contato, ativo FROM ' . $xoopsDB->prefix('cadastros') . ' ORDER BY ativo DESC, nome ASC');

    $class = 'even';

    while (list($id, $nome, $endereco, $bairro, $contato, $ativo) = $xoopsDB->fetchRow($result)) {
        $contato = htmlspecialchars($contato, ENT_QUOTES | ENT_HTML5);

        $nome = htmlspecialchars($nome, ENT_QUOTES | ENT_HTML5);

        $endereco = htmlspecialchars($endereco, ENT_QUOTES | ENT_HTML5);

        $bairro = htmlspecialchars($bairro, ENT_QUOTES | ENT_HTML5);

        $check1 = '';

        $check2 = '';

        $validacontato = mb_strpos($contato, '@');

        $validacontato2 = mb_strpos($contato, '.');

        if (1 == $ativo) {
            $check1 = "selected='selected'";
        } else {
            $check2 = "selected='selected'";
        }

        echo '<tr>';

        echo "<td class='$class'>" . $nome . '</td>';

        echo "<td class='$class'>" . $endereco . '</td>';

        echo "<td class='$class'>" . $bairro . '</td>';

        if (false === $validacontato) {
            if (true === $validacontato2) {
                echo "<td class='$class' width='10%' align='center' valign='middle'><a href=http://" . $contato . " target='_blank'>" . $contato . '</a></td>';
            } else {
                echo "<td class='$class' width='10%' align='center' valign='middle'>" . $contato . '</td>';
            }
        } else {
            echo "<td class='$class' width='10%' align='center' valign='middle'><a href=mailto:" . $contato . '>' . $contato . '</a></td>';
        }

        echo "<td class='$class' width='3%' align='center'>
		<select size='1' name='ativo[$id]'> <option value='1' " . $check1 . ">Sim</option><option value='0' " . $check2 . '>Não</option></select>
		';

        echo "<td class='$class' width='3%' align='center'>
		<a href='index.php?op=editarCadastro&amp;id=" . $id . "'>Editar</a><br>--<br><a href='index.php?op=removerCadastro&amp;id=" . $id . "'>Deletar</a>
		</td></tr>";

        $class = ('odd' == $class) ? 'even' : 'odd';
    }

    echo "<tr><td class='foot' colspan='7' align='right'>
 <input type='hidden' name='op' value='atualizaCadastros'>
	<input type='button' name='button' onclick=\"location='index.php?op=AdicionarCadastro'\" value='" . _CAD_ADM_ADICIONA . "'>
	<input type='submit' name='submit' value='" . _CAD_ADM_ATUALIZA . "'>
	</td></tr></table></form>";

    xoops_cp_footer();
}

function atualizaCadastros($ativo)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    foreach ($ativo as $id => $fe) {
        if (isset($id) && is_numeric($id)) {
            $xoopsDB->query('UPDATE ' . $xoopsDB->prefix('cadastros') . " SET ativo='$fe' WHERE id=$id");
        }
    }

    redirect_header('./index.php', 1, _CAD_ATUALIZADO);

    exit();
}

function AdicionarCadastro()
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    echo '<h4>' . _CAD_ADM_ADICIONA . '</h4>';

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $form = new XoopsThemeForm(_CAD_ADM_ADICIONA, 'addform', 'index.php');

    $formnome = new XoopsFormText(_CAD_ADM_NOME, 'nome', 50, 50);

    $formendereco = new XoopsFormText(_CAD_ADM_ENDERECO, 'endereco', 50, 100);

    $formbairro = new XoopsFormText(_CAD_ADM_BAIRRO, 'bairro', 50, 50);

    $formcontato = new XoopsFormText(_CAD_ADM_CONTATO . '(E-mail ou site)', 'contato', 50, 50);

    $formativo = new XoopsFormSelect(_CAD_ADM_ATIVO, 'ativo');

    $formativo->addOption('1', _CAD_SIM);

    $formativo->addOption('0', _CAD_NAO);

    $op_hidden = new XoopsFormHidden('op', 'adiciona');

    $submit_button = new XoopsFormButton('', 'submit', _CAD_ADM_ADICIONA, 'submit');

    $form->addElement($formnome, true);

    $form->addElement($formendereco);

    $form->addElement($formbairro);

    $form->addElement($formcontato);

    $form->addElement($formativo);

    $form->addElement($op_hidden);

    $form->addElement($submit_button);

    $form->display();

    xoops_cp_footer();
}

function adiciona($nome, $endereco, $bairro, $contato, $ativo)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $myts = MyTextSanitizer::getInstance();

    $nome = isset($nome) ? trim($nome) : '';

    $endereco = isset($endereco) ? trim($endereco) : '';

    $bairro = isset($bairro) ? trim($bairro) : '';

    $contato = isset($contato) ? trim($contato) : '';

    if ('' == $nome) {
        redirect_header('index.php', 1, _CAD_ERRO1);

        exit();
    }

    $nome = $myts->addSlashes($nome);

    $endereco = $myts->addSlashes($endereco);

    $bairro = $myts->addSlashes($bairro);

    $contato = $myts->addSlashes($contato);

    $ativo = isset($ativo) ? (int)$ativo : 0;

    $sql = 'INSERT INTO ' . $xoopsDB->prefix('cadastros') . " VALUES (NULL, '$nome', '$endereco', '$bairro', '$contato', $ativo)";

    if ($xoopsDB->query($sql)) {
        redirect_header('index.php', 1, _CAD_ATUALIZADO);
    } else {
        redirect_header('index.php', 1, _CAD_NAO_ATUALIZADO);
    }

    exit();
}

function editarCadastro($id)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    echo '<h4>' . _CAD_ADM_EDITA . '</h4>';

    $result = $xoopsDB->query('SELECT nome, endereco, bairro, contato, ativo FROM ' . $xoopsDB->prefix('cadastros') . " WHERE id=$id");

    [$nome, $endereco, $bairro, $contato, $ativo] = $xoopsDB->fetchRow($result);

    $nome = htmlspecialchars($nome, ENT_QUOTES | ENT_HTML5);

    $endereco = htmlspecialchars($endereco, ENT_QUOTES | ENT_HTML5);

    $bairro = htmlspecialchars($bairro, ENT_QUOTES | ENT_HTML5);

    $contato = htmlspecialchars($contato, ENT_QUOTES | ENT_HTML5);

    require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $form = new XoopsThemeForm(_CAD_ADM_EDITA, 'editform', 'index.php');

    $formnome = new XoopsFormText(_CAD_ADM_NOME, 'nome', 50, 50, $nome);

    $formendereco = new XoopsFormText(_CAD_ADM_ENDERECO, 'endereco', 50, 100, $endereco);

    $formbairro = new XoopsFormText(_CAD_ADM_BAIRRO, 'bairro', 50, 50, $bairro);

    $formcontato = new XoopsFormText(_CAD_ADM_CONTATO, 'contato', 50, 50, $contato);

    $formativo = new XoopsFormSelect(_CAD_ADM_ATIVO, 'ativo', $ativo);

    $formativo->addOption('1', _CAD_SIM);

    $formativo->addOption('0', _CAD_NAO);

    $id_hidden = new XoopsFormHidden('id', $id);

    $op_hidden = new XoopsFormHidden('op', 'AtualizarCadastro');

    $submit_button = new XoopsFormButton('', 'submit', _CAD_ADM_ATUALIZA, 'submit');

    $form->addElement($formnome, true);

    $form->addElement($formendereco);

    $form->addElement($formbairro);

    $form->addElement($formcontato);

    $form->addElement($formstat);

    $form->addElement($id_hidden);

    $form->addElement($op_hidden);

    $form->addElement($submit_button);

    $form->display();

    xoops_cp_footer();
}

function AtualizarCadastro($id, $nome, $endereco, $bairro, $contato, $ativo)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    $myts = MyTextSanitizer::getInstance();

    $nome = isset($nome) ? trim($nome) : '';

    $endereco = isset($endereco) ? trim($endereco) : '';

    $bairro = isset($bairro) ? trim($bairro) : '';

    $contato = isset($contato) ? trim($contato) : '';

    $id = (int)$id;

    $ativo = isset($ativo) ? (int)$ativo : 0;

    if ('' == $nome || empty($id)) {
        redirect_header("index.php?op=edit_cadastro&id=$id", 1, _CAD_ERRO1);

        exit();
    }

    $nome = $myts->addSlashes($nome);

    $endereco = $myts->addSlashes($endereco);

    $bairro = $myts->addSlashes($bairro);

    $contato = $myts->addSlashes($contato);

    if ($xoopsDB->query('UPDATE ' . $xoopsDB->prefix('cadastros') . " SET nome='$nome', endereco='$endereco', bairro='$bairro', contato='$contato', ativo=$ativo WHERE id=$id")) {
        redirect_header('index.php', 1, _CAD_ATUALIZADO);
    } else {
        redirect_header('index.php', 1, _CAD_NAO_ATUALIZADO);
    }

    exit();
}

function removerCadastro($id, $del = 0)
{
    $xoopsDB = XoopsDatabaseFactory::getDatabaseConnection();

    if (1 == $del) {
        $sql = sprintf('DELETE FROM %s WHERE id = %u', $xoopsDB->prefix('cadastros'), $id);

        if ($xoopsDB->query($sql)) {
            redirect_header('index.php', 1, _CAD_ATUALIZADO);
        } else {
            redirect_header('index.php', 1, _CAD_NAO_ATUALIZADO);
        }

        exit();
    }

    xoops_cp_header();

    echo '<h4>' . _CAD_ADM_DELETA . '</h4>';

    xoops_confirm(['op' => 'removerCadastro', 'id' => $id, 'del' => 1], 'index.php', _CAD_CONFIRMA_DEL);

    xoops_cp_footer();
}

$op = '';
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

if (isset($_GET['op'])) {
    $op = $_GET['op'];

    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
    }
}
switch ($op) {
    case 'AdicionarCadastro':
        AdicionarCadastro();
        break;
    case 'AtualizarCadastro':
        AtualizarCadastro($id, $nome, $endereco, $bairro, $contato, $ativo);
        break;
    case 'adiciona':
        adiciona($nome, $endereco, $bairro, $contato, $ativo);
        break;
    case 'removerCadastro':
        removerCadastro($id, $del);
        break;
    case 'editarCadastro':
        editarCadastro($id);
        break;
    case 'atualizaCadastros':
        atualizaCadastros($ativo);
        break;
    default:
        cadastrosAdmin();
        break;
}
