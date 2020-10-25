<?php

#####################################################################
# --->     Mastop InfoDigital - Paixão por Informática              #
# --->                                                              #
# --->   Módulo Cadastros - Fernando Santos (aka topet05)           #
# --->            http://www.mastop.com.br                          #
# --->arquivo: cadastros.php     ultima alteração: 10/01/2004       #
#####################################################################
require_once XOOPS_ROOT_PATH . '/kernel/object.php';

class MeuCadastro extends XoopsObject
{
    public $db;

    // construtor

    public function __construct($id = null)
    {
        $this->db = XoopsDatabaseFactory::getDatabaseConnection();

        $this->initVar('id', XOBJ_DTYPE_INT, null, false);

        $this->initVar('nome', XOBJ_DTYPE_TXTBOX, null, false);

        $this->initVar('endereco', XOBJ_DTYPE_TXTBOX, null, false);

        $this->initVar('bairro', XOBJ_DTYPE_TXTBOX, null, true);

        $this->initVar('contato', XOBJ_DTYPE_TXTBOX, null, true);

        if (!empty($id)) {
            if (is_array($id)) {
                $this->assignVars($id);
            } else {
                $this->load((int)$id);
            }
        }
    }

    public function load($id)
    {
        $sql = 'SELECT * FROM ' . $this->db->prefix('cadastros') . " WHERE id=$id and ativo=1";

        $linha = $this->db->fetchArray($this->db->query($sql));

        $this->assignVars($linha);
    }

    public function PegaTodosCadastros($criterio = [], $objeto = false, $classifica = 'nome', $como = 'ASC', $limite = 0, $comeco = 0)
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $ret = [];

        $where_query = '';

        if (is_array($criterio) && count($criterio) > 0) {
            $where_query = ' WHERE';

            foreach ($criterio as $c) {
                $where_query .= " $c AND";
            }

            $where_query = mb_substr($where_query, 0, -4);
        } elseif (!is_array($criterio)) {
            $where_query = ' WHERE ' . $criterio;
        }

        if (!$objeto) {
            $sql = 'SELECT id FROM ' . $db->prefix('cadastros') . "$where_query ORDER BY $classifica $como";

            $result = $db->query($sql, $limite, $comeco);

            while (false !== ($linha = $db->fetchArray($result))) {
                $ret[] = $linha['id'];
            }
        } else {
            $sql = 'SELECT * FROM ' . $db->prefix('cadastros') . '' . $where_query . " ORDER BY $classifica $como";

            $result = $db->query($sql, $limite, $comeco);

            while (false !== ($linha = $db->fetchArray($result))) {
                $ret[] = new self($linha);
            }
        }

        return $ret;
    }
}
