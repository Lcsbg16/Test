<?php

use MailerSend\Helpers\Builder\Attachment;
use MailerSend\Helpers\Builder\EmailParams;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\MailerSend;

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
     * @param string $assunto assunto do email
     * @param string $destinatario destinatario do email ou array com lista de destinatários
     * @param string $mensagem mensagem a ser enviada no email
     * @param string $email Objeto de email vindo da classe do codeIgniter email
     * @param string $remetente nome que aparecerá como remetente do email
     */
    public function enviarEmail($assunto, $destinatario, $mensagem, $remetente = "", $getAnexo = NULL)
    {
        $mensagem .= '<p><small>Aten&ccedil;&atilde;o: N&atilde;o responda a este e-mail.</small></p>';

        if (EMAIL_PROTOCOLO == 'smtp')
        {
            return $this->enviarEmailPhpMailer($assunto, $destinatario, $mensagem, $remetente, $getAnexo);
        }
        elseif (EMAIL_PROTOCOLO == 'mailersend')
        {
            return $this->enviarEmailMailerSend($assunto, $destinatario, $mensagem, $remetente, $getAnexo);
        }
        else
        {
            throw new Exception('Protocolo de envio de e-mail desconhecido!');
        }
    }

    private function enviarEmailMailerSend($assunto, $destinatario, $mensagem, $remetente, $getAnexo)
    {
        $mailersend = new MailerSend(['api_key' => EMAIL_MAILERSEND_API_KEY]);

        $recipients = [
            new Recipient($destinatario, NULL),
        ];

        if ($getAnexo)
        {
            $attachments = [
                new Attachment(file_get_contents($getAnexo), basename($getAnexo))
            ];
        }

        $emailParams = (new EmailParams())
                ->setFrom($remetente)
                ->setFromName(EMAIL_FROM_NOME)
                ->setRecipients($recipients)
                ->setSubject($assunto)
                ->setHtml($mensagem)
                ->setText(strip_tags($mensagem));

        if ($getAnexo)
        {
            $emailParams->setAttachments($attachments);
        }

        return $mailersend->email->send($emailParams);
    }

    private function enviarEmailPhpMailer($assunto, $destinatario, $mensagem, $remetente, $getAnexo)
    {
        //var_dump(func_get_args());
        $mensagem = $this->processaVariaveis($mensagem);
        try
        {

            $mail = new PHPMailer\PHPMailer\PHPMailer;

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
