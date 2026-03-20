<?php

require_once '/var/www/html/cidex/phpmailer/phpmailer/PHPMailerAutoload.php';

class MailClass {
    
    const USUARIO = 'suporte@ceadex.eb.mil.br';
    const SENHA = '@c3ss0Sup0rt&';
    const HOST = 'smtp.webmail.eb.mil.br';
    const PORTA = 587;
    const PROTOCOLO = 'tls';
    
    public static function enviaEmail($deEmail,$deNome,$replayEmail, $replayNome, $para, $assunto,$corpo ) {        
        $mail = new PHPMailer;
        // $mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->CharSet = "UTF-8";
        $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true)
                    );
        $mail->Host = self::HOST;  // Specify main and backup SMTP servers
        $mail->SMTPAuth = TRUE;                               // Enable SMTP authentication
        $mail->Username = self::USUARIO;                 // SMTP username
        $mail->Password = self::SENHA;                           // SMTP password
        $mail->SMTPSecure = self::PROTOCOLO;                        // Enable TLS encryption, `ssl` also accepted
        $mail->Port = self::PORTA;                            // TCP port to connect to

        $mail->addReplyTo($replayEmail, $replayNome);
        $mail->setFrom($deEmail, $deNome);

        $mail->addAddress($para);     // Add a recipient
        // $mail->addReplyTo('info@example.com', 'Information');
         //$mail->addCC('suporte@ceadex.eb.mil.br');
        // $mail->addBCC('bcc@example.com');

        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $assunto;
        $mail->Body    = $corpo;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        return $mail->send();
    }
    public static function enviaEmailSecretaria($para, $assunto,$corpo ) {        
       return self::enviaEmail('suporte@ceadex.eb.mil.br', 'CIDEx - suporte','secrctf@cidex.eb.mil.br', 'CIDEx', $para, $assunto, $corpo);  
    }
    
    public static function getMensagemPadrao($texto){        
            $mensagem = '';
            $mensagem .= '<!DOCTYPE html>';
            $mensagem .= '<html>';
            $mensagem .= '    <head>';
            $mensagem .= '        <title>Centro de Idiomas do Exército</title>';
            $mensagem .= '        <meta name="viewport" content="width=device-width, initial-scale=1.0">';
            $mensagem .= '        <meta name="format-detection" content="telephone=no">';
            $mensagem .= '        <meta name="format-detection" content="date=no">';
            $mensagem .= '        <meta name="format-detection" content="address=no">';
            $mensagem .= '        <meta name="format-detection" content="email=no">';
            $mensagem .= '    </head>';
            $mensagem .= '    <body>';
            $mensagem .= '        <table style="margin: auto auto; padding-top: 20px; border-spacing: 0px;" width="750px" >';
            $mensagem .= '            <thead>';
            $mensagem .= '            <tr style="background: #1659bf; color: white; padding: 20px; font-family: open_sansbold,Open Sans,Arial,Helvetica,sans-serif; letter-spacing: 0.72px">';
            $mensagem .= '                <th>';
            $mensagem .= '                    <img src="http://www.cidex.eb.mil.br/images/exemplo_emblemas/cidex.png" alt="CIDEx" style="margin-top: 10px;">';
            $mensagem .= '                </th>';
            $mensagem .= '                <th style="text-align: left">';
            $mensagem .= '                    <span style="margin-top: 10px; margin-bottom: 0px; font-size: 10pt;font-weight: normal;">Ministério da Defesa - Departamento de Educação e Cultura do Exército</span>';
            $mensagem .= '                    <h1 style="margin-top: 0px; margin-bottom: 0px">Centro de Idiomas do Exército</h1>';
            $mensagem .= '                    <span style="margin-top: 0px; margin-bottom: 0px; font-size: 11pt;font-weight: normal;">DIRETORIA DE EDUCAÇÃO TÉCNICA MILITAR</span>';
            $mensagem .= '                </th>';
            $mensagem .= '            </tr>';
            $mensagem .= '            <tr style="background: #0f4098; color: white; padding: 20px; font-family: Georgia, serif; letter-spacing: 0.72px">';
            $mensagem .= '                <th colspan="4">&nbsp;</th>';
            $mensagem .= '            </tr>';
            $mensagem .= '            </thead>';
            $mensagem .= '            <tbody>';
            $mensagem .= '                <tr>';
            $mensagem .= '                    <td style="color: #333333; padding-bottom: 13px; font-family:  open_sansbold,Open Sans,Arial,Helvetica,sans-serif; font-size: 13px; mso-line-height-rule: exactly; line-height: 32px; font-weight: 400; text-align: justify;" align="left" valign="top" colspan="2">';
            $mensagem .= $texto;
            $mensagem .= '                    </td>';
            $mensagem .= '                </tr>';
            $mensagem .= '            </tbody>';
            $mensagem .= '            <tfoot>';
            $mensagem .= '                <tr>';
            $mensagem .= '                    <td colspan="2" style="background: #1659bf; color: white; padding: 12px; font-family:  open_sansbold,Open Sans,Arial,Helvetica,sans-serif; letter-spacing: 0.72px" align="center" height="62" valign="middle">';
            $mensagem .= '                        Telefone: (021) 3223-5054 / 3223-5024 | E-mail: <a href="mailto:secrctf@cidex.eb.mil.br" target="_blank" style="color: white;text-decoration: none;">secrctf@cidex.eb.mil.br</a> <br>';
            $mensagem .= '                        Site: <a href="http://www.cidex.eb.mil.br/" target="_blank" style="color: white;text-decoration: none;">http://www.cidex.eb.mil.br/</a>';
            $mensagem .= '                    </td>';
            $mensagem .= '                </tr>';
            $mensagem .= '                <tr style="background: #0041b2; color: white; padding: 8px; font-family:  open_sansbold,Open Sans,Arial,Helvetica,sans-serif; letter-spacing: 0.72px" align="center" height="40" valign="middle">';
            $mensagem .= '                    <td colspan="2" align="center" >Centro de Idiomas do Exército</td>';
            $mensagem .= '                </tr>';
            $mensagem .= '            </tfoot>';
            $mensagem .= '    </table>';
            $mensagem .= '    </body>';
            $mensagem .= '</html>';
            
            return $mensagem;
    }
    
    
}