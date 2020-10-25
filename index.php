<?php

#####################################################################
# --->     Mastop InfoDigital - Paixão por Informática              #
# --->                                                              #
# --->   Módulo Cadastros - Fernando Santos (aka topet05)           #
# --->            http://www.mastop.com.br                          #
# --->arquivo: index.php ultima alteração: 10/01/2004               #
#####################################################################

require __DIR__ . '/header.php';

$GLOBALS['xoopsOption']['template_main'] = 'cadastros_index.html';
require XOOPS_ROOT_PATH . '/header.php';    // Inclui o Header
$esc = new MeuCadastro();
if ($xoopsUser) {
    if ($xoopsUser->isAdmin()) {
        $admin = 1;
    } else {
        $admin = 0;
    }
}
$query = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('cadastros') . ' WHERE ativo=1');
[$numrows] = $xoopsDB->fetchRow($query);
$cadastros = $esc->PegaTodosCadastros('ativo = 1', true);
foreach ($cadastros as $cad_obj) {
    $array_cadastros[] = [
        'id' => $cad_obj->getVar('id'),
'nome' => $cad_obj->getVar('nome'),
'endereco' => $cad_obj->getVar('endereco'),
'bairro' => $cad_obj->getVar('bairro'),
'contato' => $cad_obj->getVar('contato'),
    ];
}
$cadastro_count = count($array_cadastros);

for ($i = 0; $i < $cadastro_count; $i++) {
    if (!empty($array_cadastros[$i]['contato'])) {
        $validacontato = mb_strpos($array_cadastros[$i]['contato'], '@');

        $validacontato2 = mb_strpos($array_cadastros[$i]['contato'], '.');

        if (false === $validacontato) {
            if (true === $validacontato2) {
                $cad_contato = '<a href="http://' . $array_cadastros[$i]['contato'] . '" target="_blank">' . $array_cadastros[$i]['contato'] . '</a>';
            } else {
                $cad_contato = $array_cadastros[$i]['contato'];
            }
        } else {
            $cad_contato = '<a href="mailto:' . $array_cadastros[$i]['contato'] . '">' . $array_cadastros[$i]['contato'] . '</a>';
        }
    } else {
        $cad_contato = 'Não Disponível';
    }

    if (empty($array_cadastros[$i]['endereco'])) {
        $cad_endereco = 'Não Disponível';
    } else {
        $cad_endereco = $array_cadastros[$i]['endereco'];
    }

    if (empty($array_cadastros[$i]['bairro'])) {
        $cad_bairro = 'Não Disponível';
    } else {
        $cad_bairro = $array_cadastros[$i]['bairro'];
    }

    $cadastro[$i]['id'] = $array_cadastros[$i]['id'];

    $cadastro[$i]['nome'] = $array_cadastros[$i]['nome'];

    $cadastro[$i]['endereco'] = $cad_endereco;

    $cadastro[$i]['bairro'] = $cad_bairro;

    $cadastro[$i]['contato'] = $cad_contato;

    if (1 == $admin) {
        $cadastro[$i]['admin_option'] = "[<a href='admin/index.php?op=editarCadastro&amp;id=" . $array_cadastros[$i]['id'] . "'>" . _CAD_EDITAR . "</a>] [<a href='admin/index.php?op=removerCadastro&id=" . $array_cadastros[$i]['id'] . "'>" . _CAD_DELETAR . '</a>]';
    }

    $xoopsTpl->append('cadastros', $cadastro[$i]);
}
$xoopsTpl->assign(
    [
        'cad_nome' => _CAD_NOME,
'cad_endereco' => _CAD_ENDERECO,
'cad_bairro' => _CAD_BAIRRO,
'cad_contato' => _CAD_CONTATO,
'cad_titulo' => _CAD_TITULO,
'cad_baixo' => _CAD_BAIXO,
'titulo_admin' => _CAD_ADMIN,
'admin' => $admin,
'cad_semcad' => _CAD_SEMCAD,
'cad_desc' => _CAD_DESC,
    ]
);
require_once XOOPS_ROOT_PATH . '/footer.php';
