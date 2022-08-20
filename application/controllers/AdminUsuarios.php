<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BaseCrudController.php';

/**
 * Controller do cadastro de usuários
 */
class AdminUsuarios extends BaseCrudController
{

    public function index()
    {
        try
        {

            $crud = new AppGroceryCRUD();

            // Configurações gerais do cadastro
            $crud->set_theme(self::DEFAULT_CRUD_THEME);
            $crud->set_table('usuario');
            $crud->set_subject('Usuários');

            // Validações
            $crud->required_fields('nome', 'username', 'cpf_passaporte');
            $crud->unique_fields('username');

            // Nomes dos campos
            $crud->display_as('email', 'E-mail');
            $crud->display_as('username', 'Usuário');

            $crud->display_as('_grupos', 'Grupos de Acesso');

            // Tipos de campos
            $crud->change_field_type('senha', 'password');
            $crud->field_type('ativo', 'true_false', array('Não', 'Sim'));
            $crud->unset_texteditor('obs');

            // Configurações da listagems
            $crud->columns('username', 'nome', '_grupos');

            // Callbacks de processamento
            $crud->callback_before_insert(array($this, 'encrypt_password_callback'));
            $crud->callback_before_update(array($this, 'encrypt_password_callback'));

            // Callbacks de campo
            $crud->callback_edit_field('senha', array($this, 'show_password_field'));

            // Relacionamentos
            $crud->set_relation_n_n('_grupos', 'usuario_possui_grupo', 'grupo', 'usuario_id', 'grupo_usuarios_id', 'nome');

            $this->_crud_output($crud);
        }
        catch (Exception $e)
        {
            show_error($e->getMessage() . ' --- ' . $e->getTraceAsString());
        }
    }

    public function encrypt_password_callback($post_array, $primary_key = null)
    {
        $this->load->helper('security');

        if (!empty($post_array['senha']))
        {
            $post_array['senha'] = do_hash($post_array['senha'], 'sha1');
        }
        else
        {
            unset($post_array['senha']);
        }

        return $post_array;
    }

    public function show_password_field($value)
    {
        if ($this->grocery_crud->getState() == 'read')
        {
            return '-';
        }
        else
        {
            return "<input type='password' class='form-control' name='senha' value='' autocomplete='new-password' />";
        }
    }

}
