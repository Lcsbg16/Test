<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'BasePrivateController.php';

/**
 * Controller padrão de telas CRUD
 */
abstract class BaseCrudController extends BasePrivateController
{

    const DEFAULT_CRUD_THEME = 'bootstrap-v4';

    /**
     *
     * @var AppGroceryCRUD
     */
    protected $_crud = null;

    /**
     *
     * @var string
     */
    protected $permissao_acesso = 'ADMIN';

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Grocery_CRUD');
        $this->load->library('AppGroceryCRUD');
    }

    protected function _crud_output($crud, $view_variables = array())
    {
        if ($this->_crud == null)
        {
            $this->_crud = $crud;
        }
        $screen_output = $this->_setViewVariables($crud, $view_variables);
        $this->_loadDefaultView($screen_output);
    }

    protected function _setViewVariables($crud, $view_variables = array())
    {
        if ($crud == null)
        {
            $screen_output = (object) array('screen_output' => '', 'js_files' => array(), 'css_files' => array());
        }
        else
        {
            $screen_output               = $crud->render();
            $screen_output->screen_title = $crud->getSubject();
        }

        $view_variables = array_merge($view_variables, $this->gerarVariaveisViewPadrao());

        foreach ($view_variables as $current_var => $current_value)
        {
            $screen_output->$current_var = $current_value;
        }
        return $screen_output;
    }

    protected function _loadDefaultView($output)
    {
        $this->loadSmartyView('crud_default', (array) $output);
    }

    public function index()
    {
        $this->_crud_output(null);
    }

    /**
     * MYTODO: doc
     * @param type $post_array
     * @param type $primary
     */
    public function crudLog($post_array, $primary)
    {
        $this->load->model('LoginModel');
        $user_info = $this->LoginModel->getDadosUsuarioLogado();
        $log       = array(
            'user_id'    => $user_info['usuario_id'],
            'ip'         => $this->input->server('REMOTE_ADDR'),
            'controller' => get_class($this),
            'datahora'   => date('Y-m-d H:i:s'),
            'operacao'   => $this->_crud->getState(),
            'dados'      => serialize($post_array)
        );
        return $this->db->insert('crud_log', $log);
    }

    /**
     * TODO: doc
     * @param type $post_array
     * @param type $primary
     */
    public function crudLogDelete($primary)
    {
        return $this->crudLog(array(), $primary);
    }

    public function callbackAfterInsert($post_array, $primary)
    {
        return $this->callbackAfterProcess($post_array, $primary);
    }

    public function callbackAfterUpdate($post_array, $primary)
    {
        return $this->callbackAfterProcess($post_array, $primary);
    }

    public function callbackAfterProcess($post_array, $primary)
    {
        return $this->crudLog($post_array, $primary);
    }

    public function callbackAfterDelete($post_array, $primary)
    {
        return $this->crudLogDelete($post_array, $primary);
    }

    /**
     * TODO: doc
     */
    protected function _adicionarCallbacksDePosProcessamentoPadrao($crud)
    {
        $this->_crud = $crud;
        $crud->callback_after_insert(array($this, 'callbackAfterInsert'));
        $crud->callback_after_delete(array($this, 'crudLogDelete'));
        $crud->callback_after_update(array($this, 'callbackAfterUpdate'));
    }

    protected function _adicionarCallbacksDePreProcessamentoPadrao($crud)
    {
        $this->_crud = $crud;
        $crud->callback_before_insert(array($this, 'callbackBeforeInsert'));
    }

    /**
     * MYTODO: doc
     * @param type $text
     * @return string
     */
    static protected function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }

    public function callbackCampoEvento($value = '', $primary_key = null)
    {
        if (!$value)
        {
            $dados_evento = $this->EventosModel->getEventoAtualAdmin();
        }
        else
        {
            $dados_evento = $this->EventosModel->getEventoArray($value);
        }
        $variaveis_view                  = [];
        $variaveis_view['valor']         = $dados_evento['evento_id'];
        $variaveis_view['nome_campo']    = 'evento_id'; // O Grocery CRUD não permite receber o nome do campo na callback
        $variaveis_view['valor_exibido'] = $dados_evento['nome'];

        return $this->loadSmartyView('campoValorFixo', $variaveis_view, true);
    }

}
