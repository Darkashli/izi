<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'third_party/swift_mailer/swift_required.php';

/**
 * Send a ticket email
 * @param type $toMail
 * @param type $toName
 * @param type $subject
 * @param type $body
 * @param type $pdf_content
 * @param type $filename
 * @return boolean result
 */
function sendTicket($fromMail, $fromName, $toMail, $toName, $subject, $body, $attachments, $bccMail, $bccName) {
    try {
        $ci = & get_instance();

        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');

        /**
         * Mailing via iziFactuur (Directadmin)
         */
        $https['ssl']['verify_peer'] = FALSE;
        $https['ssl']['verify_peer_name'] = FALSE;

        $transport = Swift_SmtpTransport::
                newInstance($ci->config->config['smtp_host'], $ci->config->config['smtp_port'], $ci->config->config['smpt_crypto'])
                ->setUsername($ci->config->config['smtp_user'])
                ->setPassword($ci->config->config['smtp_pass'])
                ->setStreamOptions($https);

        //Create the message
        $message = Swift_Message::newInstance();


        $from = array($fromMail => $fromName);
        $to = array($toMail => $toName);

        $message->setSubject($subject);
        $message->setFrom($from);
        $message->setTo($to);
        // $message->setReplyTo(array('joost@commpro.nl' => 'Joost Tempelman'));
        $message->setBody($body, 'text/html');

        // Is er een BCC e-mail adres en naam meegegeven?
        if ($bccMail != '') {
            $bccMails = explode(';', $bccMail);
            foreach ($bccMails as $bccMailsValue) {
                $message->setBcc($bccMailsValue);
            }
        }

        if (isset($attachments)) {
            // Loop door alle mail bijlagen en voeg ze toe aan het bericht.
            foreach ($attachments as $attachment) {
                $message->attach(Swift_Attachment::newInstance($attachment['content'], $attachment['fileName'], 'application/pdf'));
            }
        }

        // Attach the generated PDF from earlier
        //Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        //Send the message
        $result = $mailer->send($message);
        return $result;
    } catch (Exception $e) {
        // echo $e->getMessage() . "<br />";
        // echo "<pre>";
        // print_r($e->getTraceAsString());
        // echo "</pre>";
        return false;
    }
}

function sendNewTicket($toMail, $toName, $ticketId, $ticketRuleId) {
    try {
        $ci = & get_instance();

        $businessId = $ci->session->userdata('user')->BusinessId;
        $business = getBusiness($businessId);
        $ticket = $ci->Tickets_model->getTicket($ticketId, $businessId)->row();

        if ($ticket == null) {
            $ci->session->set_tempdata('err_message', 'Dit ticket bestaat niet', 300);
            $ci->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("customers/index/work");
        }

        $ticketRule = $ci->Tickets_model->getTicketRule($ticketRuleId, $ticketId, $businessId)->row();
        $ticketAttachments = $ci->Attachments_model->getTicketRuleAttachments($ticketRuleId, $businessId)->result();

        $from = array($business->WorkEmail => $business->Name);
        $to = array($toMail => $toName);

        // Get the body text en replace special chracters
        $body = $business->WorkEmailTextBC;


        // Klant inforamtie
        $body = str_replace('{CONTACTKLANT}', getContactName($ticketRule->ContactId), $body);
        $body = str_replace('{KLANTNAAM}', getCustomer($ticketRule->CustomerId), $body);

        // Bedrijf informatie
        $body = str_replace('{COLLEGA}', getAccountName($ticketRule->UserId), $body);

        // Ticket informatie
        $body = str_replace('{MELDINGKLANT}', $ticket->CustomerNotification, $body);
        $body = str_replace('{ACTIEMEDEWERKER}', $ticketRule->ActionUser, $body);
        $body = str_replace('{INTERNENOTITIE}', $ticketRule->InternalNote, $body);
        $body = str_replace('{DATUM}', date('d-m-Y', $ticketRule->Date), $body);
        $body = str_replace('{TIJD}', date('H:i', $ticketRule->StartWork), $body);
        $body = str_replace('{STATUS}', getStatus($ticketRule->Status)->Description, $body);
        $body = str_replace('{TOEGEWEZENAAN}', getAccountName($ticketRule->UserIdLink), $body);
        $body = str_replace('{TICKETURL}', base_url() . 'work/update/' . $ticketId, $body);

        $subject = "Servicemelding helpdesk nr. " . $ticket->Number;

        $https['ssl']['verify_peer'] = FALSE;
        $https['ssl']['verify_peer_name'] = FALSE;

        $transport = Swift_SmtpTransport::
                newInstance($ci->config->config['smtp_host'], $ci->config->config['smtp_port'], $ci->config->config['smpt_crypto'])
                ->setUsername($ci->config->config['smtp_user'])
                ->setPassword($ci->config->config['smtp_pass'])
                ->setStreamOptions($https);

        //Create the message
        $message = Swift_Message::newInstance();
        $message->setFrom($from);
        $message->setTo($to);
        $message->setSubject($subject);
        $message->setBody($body, 'text/html');

        // Add attachments.
        foreach ($ticketAttachments as $ticketAttachment) {
            $message->attach(Swift_Attachment::fromPath("./uploads/$business->DirectoryPrefix/tickets/T$ticketId/$ticketAttachment->Name"));
        }

        //Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        //Send the message
        $result = $mailer->send($message);
        return $result;
    } catch (Exception $e) {
        echo $e->getMessage() . "<br />";
        echo "<pre>";
        print_r($e->getTraceAsString());
        echo "</pre>";
        return false;
    }
}

function sendNewUser($userId, $password) {
    try {
        $ci = & get_instance();

        $ci->load->model('users/Users_model', '', TRUE);
        $businessId = $ci->session->userdata('user')->BusinessId;
        $business = getBusiness($businessId);

        $https['ssl']['verify_peer'] = FALSE;
        $https['ssl']['verify_peer_name'] = FALSE;

        $transport = Swift_SmtpTransport::
                newInstance($ci->config->config['smtp_host'], $ci->config->config['smtp_port'], $ci->config->config['smpt_crypto'])
                ->setUsername($ci->config->config['smtp_user'])
                ->setPassword($ci->config->config['smtp_pass'])
                ->setStreamOptions($https);

        $user = $ci->Users_model->getUser($userId, $businessId)->row();

        if ($user == null) {
            $ci->session->set_tempdata('err_message', 'Deze gebruiker bestaat niet', 300);
            $ci->session->set_tempdata('err_messagetype', 'danger', 300);
            redirect("settings/users");
        }

        $from = array($business->WorkEmail => $business->Name);
        $to = array($user->Email => getAccountName($userId));

        // Get the body text en replace special chracters
        $body = $business->NewUserMailText;

        $body = str_replace('{FIRSTNAME}', $user->FirstName, $body);
        $body = str_replace('{USERNAME}', $user->Username, $body);
        $body = str_replace('{PASSWORD}', $password, $body);

        $subject = "Nieuw account bij iziAccount";

        $https['ssl']['verify_peer'] = FALSE;
        $https['ssl']['verify_peer_name'] = FALSE;

        $transport = Swift_SmtpTransport::
                newInstance($ci->config->config['smtp_host'], $ci->config->config['smtp_port'], $ci->config->config['smpt_crypto'])
                ->setUsername($ci->config->config['smtp_user'])
                ->setPassword($ci->config->config['smtp_pass'])
                ->setStreamOptions($https);

        //Create the message
        $message = Swift_Message::newInstance();
        $message->setFrom($from);
        $message->setTo($to);
        $message->setSubject($subject);
        $message->setBody($body, 'text/html');

        //Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        //Send the message
        $result = $mailer->send($message);
        return $result;
    } catch (Exception $e) {
        echo $e->getMessage() . "<br />";
        var_dump($e->getTraceAsString());
        return false;
    }
}
