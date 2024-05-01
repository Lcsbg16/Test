<?php

/**
 * Classe de extensão para a Grocery_CRUD, contendo recursos adicionais
 */
class AppGroceryCRUD extends Grocery_CRUD
{

    protected $_decimal_separator = '.';
    protected $_numeric_fields = array();
    protected $default_true_false_text = array('não', 'sim');

    public function get_field_types()
    {
        // Correção para um pequeno bug do Grocery CRUD que exibe uma mensagem de erro ao exibir o registro em modo "read"
        if (!is_array($this->required_fields))
        {
            $this->required_fields = array();
        }
        return parent::get_field_types();
    }

    /**
     * MYTODO: doc
     *
     * @param type $false
     * @param type $true
     */
    public function set_default_true_false_text($false, $true)
    {
        $this->default_true_false_text = array($false, $true);
    }

    /**
     * MYTODO: doc
     * @return type
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Retorna nome da tabela sem checagem de existência
     *
     * @return string
     */
    public function get_table_name()
    {
        return $this->basic_db_table;
    }

    /**
     *
     * @return grocery_CRUD_Form_validation Retorna o objeto de validação de formulário utilizado pelo Grocery Crud
     */
    public function getFormValidation()
    {
        if ($this->form_validation === null)
        {
            $this->form_validation = new grocery_CRUD_Form_validation();
            $ci = &get_instance();
            $ci->load->library('form_validation');
            $ci->form_validation = $this->form_validation;
        }
        return $this->form_validation;
    }

    /**
     * Retorna "categoria" do stado do CRUD, send 'create' para os estados de inserção e 'update' para os de edição. Os demais estados são retornados conforme recebidos do GroceryCrud.
     *
     * @return string Categoria do estado
     */
    public function getStateCategory()
    {
        $state = $this->getState();
        if (in_array($state, array('add', 'insert', 'insert_validation')))
            return 'create';
        elseif (in_array($state, array('edit', 'update', 'update_validation')))
            return 'update';
        else
            return $state;
    }

    /**
     * Define os campos que devem ter comportamento de campos numéricos.
     *
     * @param string $decimal_separator Separador decimal
	 * @param int [optional] $decimals Número de casas decimais
     * @param string $fields Campos que devem se comportar como numéricos
     */
	public function setNumericFields($decimal_separator, $decimals)
    {
        $this->_decimal_separator = $decimal_separator;
        $crud_state = $this->getState();
        $fields = func_get_args();
        array_shift($fields);

        if (is_int($decimals))
        {
            array_shift($fields);
        }

        foreach ($fields as $curr_f)
        {
            $field_name = $curr_f;
            $field_callback = function($value = '', $primary_key = NULL) use($field_name, $decimal_separator, $crud_state, $decimals)
            {
                if (is_int($decimals))
                {
                    $formatted_value = number_format($value, $decimals, $decimal_separator, '');
                }
                else
                {
                    $formatted_value = strtr($value, '.', $decimal_separator);
                }

                if ($crud_state == 'add' || $crud_state == 'edit')
                {
                    $html = '<input type="text" step="any" value="' . $formatted_value . '" name="' . $field_name . '" id="field-' . $field_name . '">';
                }
                else
                {
                    $html = '<span class="numeric_column">' . $formatted_value . '</span>';
                }

                return $html;
            };
            $this->callback_field($curr_f, $field_callback);
            $this->callback_column($curr_f, $field_callback);
            $this->_numeric_fields[] = $curr_f;
        }
    }

    protected function db_insert($state_info)
    {
        $state_info->unwrapped_data = $this->_convertNumericFields($state_info->unwrapped_data);
        return parent::db_insert($state_info);
    }

    protected function db_update($state_info)
    {
        $state_info->unwrapped_data = $this->_convertNumericFields($state_info->unwrapped_data);
        return parent::db_update($state_info);
    }

    /**
     * Converte os campos numéricos do formato da língua escolhida para o inglês padrão do banco de dados
     *
     * @param array $post_data Array de dados enviados por POST
     * @return array Dados enviados por post com os campos numéricos convertidos
     */
    protected function _convertNumericFields($post_data)
    {
        foreach ($this->_numeric_fields as $curr_f)
        {
            if (array_key_exists($curr_f, $post_data))
            {
                $post_data[$curr_f] = preg_replace('/[^0-9' . $this->_decimal_separator . ']/i', '', $post_data[$curr_f]);
                $post_data[$curr_f] = strtr($post_data[$curr_f], $this->_decimal_separator, '.');
            }
        }
        return $post_data;
    }

    /**
     * Adiciona um campo para a tela de adição
     *
     * @param string $field_name Nome do campo
     */
    public function add_add_field($field_name)
    {
        $this->add_fields[] = $field_name;
    }

    /**
     * Adiciona um campo para a tela de edição
     *
     * @param string $field_name Nome do campo
     */
    public function add_edit_field($field_name)
    {
        $this->edit_fields[] = $field_name;
    }

    /**
     * Adiciona um campo para a tela de edição e de adição
     *
     * @param string $field_name Nome do campo
     */
    public function add_field($field_name)
    {
        $this->add_add_field($field_name);
        $this->add_edit_field($field_name);
    }

}
