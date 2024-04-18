<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'BaseController.php';

class Api extends BaseController
{

    private function autenticar()
    {
        // Verificar se as credenciais estão presentes
        if (!$this->input->server('PHP_AUTH_USER'))
        {
            $this->solicitarAutenticacao();
        }
        else
        {
            // Verificar as credenciais
            $usuario = $this->input->server('PHP_AUTH_USER');
            $senha   = $this->input->server('PHP_AUTH_PW');

            if ($this->verificarCredenciais($usuario, $senha))
            {
                // Credenciais válidas, permitir acesso
                return true;
            }
            else
            {
                // Credenciais inválidas, solicitar autenticação novamente
                $this->solicitarAutenticacao();
            }
        }
    }

    private function solicitarAutenticacao()
    {
        // Enviar cabeçalho de autenticação
        header('WWW-Authenticate: Basic realm="Área restrita"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Autenticação necessária.';
        exit;
    }

    private function verificarCredenciais($usuario, $senha)
    {
        // Substitua estas linhas com a lógica real de verificação
        $usuarioCorreto = 'broker';
        $senhaCorreta   = 'c20942da-02c5-49a7-a4cb-f5296420d257';
        return ($usuario == $usuarioCorreto && $senha == $senhaCorreta);
    }

    public function adicionarLeitura()
    {
        $this->autenticar();
        $this->load->model('EstacoesModel');
        $this->load->model('LeiturasModel');

        $postData = $this->input->post();
        $identificadorEstacao = $postData['identidade'];

        if (!$identificadorEstacao)
        {
            throw new Exception('Identificador da estação não informado!');
        }

        try
        {
            //Verificar necessidade dessa chamada, visto que o parametro UID já vem no post.
            if ($estacao = $this->EstacoesModel->getEstacaoPorIdentificador($identificadorEstacao))
            {
                
                $dadosLeitura = [
                    'datahora'          => date('Y-m-d H:i:s', $postData['timestamp']),
                    'dir_vento'         => $postData['dir_vento'] * 45,
                    'temperatura'       => $postData['temperatura'],
                    'umidade_ar'        => $postData['umidade_ar'],
                    'velocidade_vento'  => $postData['velocidade_vento'],
                    'volume_chuva'      => $postData['volume_chuva'],
                    'datahora_cadastro' => date('Y-m-d H:i:s'),
                    'payload'           => json_encode($postData)
                ];

                $this->db->db_debug = FALSE;
                $leitura_id = $this->LeiturasModel->inserirLeitura($estacao['id'], $dadosLeitura);
                
                //removendo valores para a tabela leitura_valor
                unset($postData['identidade']);
                unset($postData['timestamp']);
                unset($postData['uid']);

                foreach ($postData as $key => $value) { 
                    $this->LeiturasModel->inserirLeituraValor( $leitura_id, $key, $value);
                }

                foreach ($postData as $key => $value) { 
                    $this->LeiturasModel->inserirUltimaLeitura($estacao['id'], $leitura_id, $key, $value);
                }
               
                $error = $this->db->error();
                if ($error['code'])
                {
                    http_response_code(500);
                    echo var_dump($error['message']);
                    return;
                }

                echo 'OK';
            }
            else
            {
                throw new Exception('Estação não encontrada!');
            }
        }
        catch (Exception $e)
        {
            // Define o cabeçalho HTTP 500
            header("HTTP/1.1 500 Internal Server Error");

            // Define o tipo de conteúdo como texto para a mensagem de erro
            header('Content-Type: text/plain');

            // Mensagem de erro personalizada
            echo $e->getMessage();
        }
    }

    public function atualizarCacheLeituraCalculada()
    {
        $this->load->model('LeiturasModel');
        $this->LeiturasModel->atualizarCacheLeituraCalculada();
    }

    public function cron()
    {
        set_time_limit(0);

        $this->atualizarCacheLeituraCalculada();
    }
}
