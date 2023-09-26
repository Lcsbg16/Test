<?php

use PHPMailer\PHPMailer\PHPMailer;

/*
 * Desenvolvido pela equipe de desencolvimento de sistemas Tic/Macaé
 */

class EmailUtil
{

    private $variaveisMensagem = [];

    public function setVariaveisMensagem($variaveisMensagem)
    {
        $this->variaveisMensagem = $variaveisMensagem;
    }

    function __construct()
    {

    }

    private function processaVariaveis($mensagem)
    {
        if (!empty($this->variaveisMensagem))
        {
            foreach ($this->variaveisMensagem as $chave => $v_atual)
            {
                $mensagem = str_replace('[[' . $chave . ']]', $v_atual, $mensagem);
            }
        }
        return $mensagem;
    }

    /**
     *
     * @param type $assunto assunto do email
     * @param type $destinatario destinatario do email ou array com lista de destinatários
     * @param type $mensagem mensagem a ser enviada no email
     * @param type $email Objeto de email vindo da classe do codeIgniter email
     * * @param type $remetente nome que aparecerá como remetente do email
     */
    function enviarEmail($assunto, $destinatario, $mensagem, $remetente = "", $getAnexo = NULL)
    {
        //var_dump(func_get_args());
        $mensagem = $this->processaVariaveis($mensagem);
        try
        {

            $mail = new PHPMailer;

            //$mail->SMTPDebug = 1;

            $CI = & get_instance();

            //$this->load->library('email');
            //var_dump(EMAIL_PROTOCOLO);
            if (EMAIL_PROTOCOLO == "smtp")
            {
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                //echo 'OK';
            }
            $mail->CharSet    = EMAIL_CHARSET;
            $mail->Host       = SMTP_HOST;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = SMTP_CRYPTO;
            $mail->Port       = SMTP_PORT;
            $mail->isHTML(true);

            $mail->From = EMAIL_FROM;

            if ($remetente == "")
            {
                $mail->FromName = EMAIL_FROM_NOME;
            }
            else
            {
                $mail->FromName = $remetente;
            }

            if (!is_array($destinatario))
            {
                $mail->addAddress($destinatario);
            }
            else
            {
                foreach ($destinatario as $email)
                {
                    $mail->AddBCC($email);
                }
            }

            $mensagem      .= '<p><small>Aten&ccedil;&atilde;o: N&atilde;o responda a este e-mail.</small></p>';
//            $mail->FromName = Constante::REMETENTE;
            $mail->Subject = $assunto;
            $mail->Body    = $mensagem;
            if ($getAnexo)
            {
                $mail->AddAttachment($getAnexo);
            }

            if (!$mail->send())
            {
                new EmailUtilException("Erro ao enviar email");
                return false;
            }

            $retorno = new EmailEnviado();
            $retorno->setAssunto($assunto);
            $retorno->setDestinatario($destinatario);
            $retorno->setMensagemEnviada($mensagem);
            $retorno->setFromName($remetente);

            return $retorno;
        }
        catch (Exception $ex)
        {
            new EmailUtilException("Erro ao enviar email");
            return false;
        }
    }
}

class EmailEnviado
{

    private $assunto;
    private $destinatario;
    private $mensagemEnviada;
    private $remetente;

    public function getAssunto()
    {
        return $this->assunto;
    }

    public function getDestinatario()
    {
        return $this->destinatario;
    }

    public function getMensagemEnviada()
    {
        return $this->mensagemEnviada;
    }

    public function getFromName()
    {
        return $this->remetente;
    }

    public function setAssunto($assunto)
    {
        $this->assunto = $assunto;
    }

    public function setDestinatario($destinatario)
    {
        $this->destinatario = $destinatario;
    }

    public function setMensagemEnviada($mensagemEnviada)
    {
        $this->mensagemEnviada = $mensagemEnviada;
    }

    public function setFromName($remetente)
    {
        $this->remetente = $remetente;
    }
}

/**
 * classe de tratamento excecao
 */
class EmailUtilException extends Exception
{

}
