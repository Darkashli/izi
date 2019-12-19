<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->model('tickets/Tickets_model', '', TRUE);
        $this->load->model('tickets/Tickets_statusmodel', '', TRUE);
        $this->load->model('customers/Customers_model', '', TRUE);
        $this->load->model('customers/Customers_contactsmodel', '', TRUE);
        $this->load->model('tickets/Tickets_productmodel', '', TRUE);
        $this->load->model('tickets/Attachments_model', '', TRUE);
        $this->load->model('business/Business_model', '', TRUE);
        $this->load->model('invoices/Invoice_model', '', TRUE);
        $this->load->helper('Ticket');
    }

    public function invoiceSupplier() {
        if (!isLogged()) {
            redirect('login');
        }
        $businessId = $this->session->userdata('user')->BusinessId;
        $business = getBusiness($businessId);

        echo $business->PurchaseNumber + 1;

        $dataB = array(
            'PurchaseNumber' => $business->PurchaseNumber + 1
        );

        $this->Invoice_model->updateInvoiceNumber($businessId, $dataB);
    }

    public function mailTicket() {
        if (!isLogged()) {
            redirect('login');
        }

        $this->load->helper('mail');

        sendNewTicket('jeromy@commpro.nl', 'jeromy', '2862', '8312');
    }

    public function mailNewUser() {
        if (!isLogged()) {
            redirect('login');
        }

        $this->load->helper('mail');

        sendNewUser(3, 'ABC');
    }

    public function mail() {
        if (!isLogged()) {
            redirect('login');
        }

        $businessId = $this->session->userdata('user')->BusinessId;
        $business = $this->Business_model->getBusiness($businessId)->row();

        if (getBusiness($businessId)->ModuleTickets == 0) {
            $this->session->set_tempdata('err_message', 'U heeft hier geen rechten voor', 300);
            $this->session->set_tempdata('err_messagetype', 'warning', 300);
            redirect('dashboard');
        }

        $this->load->helper('mail');

        $subject = "Servicemelding helpdesk nr. ";

        $body = $business->WorkEmailTextBC;
        
        $https['ssl']['verify_peer'] = FALSE;
        $https['ssl']['verify_peer_name'] = FALSE;

        $transport = Swift_SmtpTransport::
            newInstance($this->config->config['smtp_host'], $this->config->config['smtp_port'], $this->config->config['smpt_crypto'])
            ->setUsername($this->config->config['smtp_user'])
            ->setPassword($this->config->config['smtp_pass'])
                ->setStreamOptions($https);

        //Create the message
        $message = Swift_Message::newInstance();


        $from = array($business->WorkEmail => $business->Name);
        // $to = array(getAccountMail(3) => getAccountName(3));
        $to = array('jeromy@commpro.nl' => 'Jeromy Hettinga');
        
        $message->setSubject($subject);
        $message->setFrom($from);
        $message->setTo($to);
        //$message->setReplyTo(array('joost@commpro.nl' => 'Joost Tempelman'));
        $message->setBody($body, 'text/html');


        // Attach the generated PDF from earlier  
        //Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        //Send the message
        $result = $mailer->send($message);

        //$mailResult = sendTicket($business->WorkEmail, $business->Name, getAccountMail(3), getAccountName(3), $subject, $body, null, null, null, null);

        if ($result == false) {
            // ERROR
            echo "Mail error <br />";
        } else {
            echo "Mail verzonden <br />";
        }
    }

    public function stream() {
        $this->load->view('test/default');
    }

    public function ajax() {
        // Turn off output buffering
        ini_set('output_buffering', 'off');
// Turn off PHP output compression
        ini_set('zlib.output_compression', false);

//Flush (send) the output buffer and turn off output buffering
//ob_end_flush();
        while (@ob_end_flush());

// Implicitly flush the buffer(s)
        ini_set('implicit_flush', true);
        ob_implicit_flush(true);

//prevent apache from buffering it for deflate/gzip
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

        for ($i = 0; $i < 1000; $i++) {
            echo ' ';
        }

        //ob_flush();
        flush();

/// Now start the program output

        for ($i = 0; $i < 10; $i++) {
            //Hard work!!
            sleep(1);
            $p = ($i + 1) * 10; //Progress
            $response = array('message' => $p . '% complete. server time: ' . date("h:i:s", time()),
                'progress' => $p);


            echo json_encode($response);
        }

        sleep(1);
        $response = array('message' => 'Complete',
            'progress' => 100);

        echo json_encode($response);
    }

    public function openSSL() {
        phpinfo();
    }

    public function destroy() {
        $this->session->unset_userdata('user');
    }

    public function ip() {
        echo $_SERVER['REMOTE_ADDR'];
    }

    public function date() {

        for ($i = 0; $i < 11; $i++) {
            $date = strtotime(date('d-m-Y', strtotime('- ' . $i . ' days')));

            echo $date . " - " . date('d-m-Y', $date) . '<br />';
        }
    }

    public function zip() {
        if (!isLogged()) {
            redirect('login');
        }

        getAddressFromZipCode('3381LM', '6a');
    }
    
    public function woocommerce()
    {
        $this->load->library('Woocommerce');
        
        $test = $this->woocommerce->test();
        
        var_dump($test);
    }
    
    public function importSalesorderCsv()
    {
        $format = 'bol.com';
        
        
        switch ($format) {
            case 'blokker':
                // Sample data
                $csvFile = "Datum aangemaakt;Bestelnummer;Aantal;Details;Status;Reden;Aanbieding SKU;Status van aanbieding;Nr. bestelpost;Eenheidsprijs;Verzendprijs;Bedrag;Totaalbedrag verzending;Totaalbedrag bestelling;Commissie (exclusief belastingen);Commissiekosten (incl. belastingen);Bedrag aan shop (inclusief belastingen) terugbetaald;Aanvullende prijsinformatie;Belastingstarief op commissie;Verzendmethode;Acceptatiedatum;Verzenddatum;Ontvangstdatum;Verzendbedrijf;Volgnummer;Tracking-URL;Verzendadres: Aanspreking;Verzendadres: Voornaam;Verzendadres: Familienaam;Verzendadres: Bedrijfsnaam;Verzendadres: Straat (1);Verzendadres: Straat (2);Verzendadres: Aanvulling;Verzendadres: Postcode;Verzendadres: Stad;Verzendadres: Staat;Verzendadres: Land;Verzendadres: Telefoonnummer;Verzendadres: Telefoonnummer 2;Verzendadres: Aanvullende informatie;Verzendadres: Interne aanvullende informatie;Facturatie-adres: Aanspreking;Facturatie-adres: Voornaam;Facturatie-adres: Familienaam;Facturatie-adres: Bedrijfsnaam;Facturatie-adres: Straat (1);Facturatie-adres: Straat (2);Facturatie-adres: Aanvulling;Facturatie-adres: Postcode;Facturatie-adres: Stad;Facturatie-adres: Staat;Facturatie-adres: Land;Facturatie-adres: Telefoonnummer;Facturatie-adres: Telefoonnummer 2;Valuta;Shop SKU;email_customer;phone_customer
25-08-18 - 10:55:31;51922101-A;1;Mondial elektrische fonduepan 8 p (1636327);Verzending in uitvoering;;7898490168033;Nieuw;159135191;49.95;0.00;49.95;0.00;49.95;3.30;3.99;45.96;;21.0000;ShippingType.default_shipmethod;27-08-18 - 09:21:18;;;;;;Mrs.;Maaike;van Rheenen;;Cyclaamrood 12;;;2718SE;Zoetermeer;;Nederland;;;;;Mrs.;Maaike;van Rheenen;;Cyclaamrood 12;;;2718SE;Zoetermeer;;Nederland;;;EUR;475;mvr87@live.nl;0615871982
24-08-18 - 16:52:15;51614119-A;1;Mondial tosti toaster 2-slots (1489694);Verzending in uitvoering;;7898490168064;Nieuw;159090880;46.95;0.00;46.95;0.00;46.95;3.15;3.81;43.14;;21.0000;ShippingType.default_shipmethod;27-08-18 - 09:24:15;;;;;;Family;belinda;edwards;;Eksterstraat 1 B;;;1223PE;Hilversum;;Nederland;;;;;Family;belinda;edwards;;Eksterstraat 1 B;;;1223PE;Hilversum;;Nederland;;;EUR;7898490168064;haarennagels@hotmail.com;0641424048
26-08-18 - 13:39:18;51943205-A;1;H.koenig wijnkoelkast 12 flessen (1604689);Verzending in uitvoering;;3760124954135;Nieuw;159250409;199.95;0.00;199.95;0.00;199.95;10.74;13.00;186.95;;21.0000;ShippingType.default_shipmethod;27-08-18 - 09:09:26;;;;;;Family;Miranda;Bos;;Brouwersweg 47;;;9646AL;Veendam;;Nederland;;;;;Family;Miranda;Bos;;Brouwersweg 47;;;9646AL;Veendam;;Nederland;;;EUR;3760124954135;mirandabosvanderveen@gmail.com;0646265032
26-08-18 - 15:56:40;51938342-A;1;PELAMATIC ELEKTRISCHE FRUIT EN GROENTE SCHILLER PRO (1924085);Verzending in uitvoering;;8437000371564;Nieuw;159215697;99.95;0.00;99.95;0.00;99.95;5.78;6.99;92.96;;21.0000;ShippingType.default_shipmethod;27-08-18 - 09:20:00;;;;;;Mr.;Ronald;Wolbers;;Bornsedijk 61;;;7559PT;Hengelo;;Nederland;;;;;Mr.;Ronald;Wolbers;;Bornsedijk 61;;;7559PT;Hengelo;;Nederland;;;EUR;8437000371564;ronaldwolbers@gmail.com;0623252217
26-08-18 - 17:33:45;51944393-A;1;H.koenig wijnkoelkast 12 flessen (1604689);Verzending in uitvoering;;3760124954135;Nieuw;159255700;199.95;0.00;199.95;0.00;199.95;10.74;13.00;186.95;;21.0000;ShippingType.default_shipmethod;27-08-18 - 09:08:49;;;;;;Mr.;Joost;Hoogewoud;;Monteverdistraat 149;;;1447NG;Purmerend;;Nederland;;;;;Mr.;Joost;Hoogewoud;;Monteverdistraat 149;;;1447NG;Purmerend;;Nederland;;;EUR;3760124954135;joost.hoogewoud@xs4all.nl;0653913542";
                
                break;
            case 'bol.com':
                // Sample data
                $csvFile = "Versienr;Verzendadres;;;;;;;;;;;Factuuradres;;;;;;;;;;;Bestelling;;;;;;;;;;Contact;;Klantinformatie
v.1.0;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
bestelnummer;aanhef_verzending;voornaam_verzending;achternaam_verzending;bedrijfsnaam_verzending;adres_verz_straat;adres_verz_huisnummer;adres_verz_huisnummer_toevoeging;adres_verz_toevoeging;postcode_verzending;woonplaats_verzending;land_verzending;aanhef_facturatie;voornaam_facturatie;achternaam_facturatie;bedrijfsnaam_facturatie;adres_fact_straat;adres_fact_huisnummer;adres_fact_huisnummer_toevoeging;adres_fact_toevoeging;postcode_facturatie;woonplaats_facturatie;land_facturatie;EAN;referentie;producttitel;aantal;prijs;besteldatum;uiterste leverdatum;exacte leverdatum;conditie;annuleringsverzoek;emailadres;telnummerbezorging;btw-nummer
4888355050;mevrouw;Iris;Brand;;Deltaweg;1170;;;2321 KX;LEIDEN;Nederland;mevrouw;Iris;Brand;;Deltaweg;1170;;;2321 KX;LEIDEN;Nederland;4016471074750;7475;Easymaxxx Mini Wasmachine;1;76,95;29-Aug-2018 11:08;03-Sep-2018;;Nieuw;Nee;2xtgfty33eg23zsrlk6sxf6q76vtti@verkopen.bol.com;;
4888352850;heer;peter;de bruijn;;Duinweg;5;A;;9163 GH;NES AMELAND;Nederland;heer;peter;de bruijn;;Duinweg;5;A;;9163 GH;NES AMELAND;Nederland;3760124954135;AGE12WV;H.Koenig Wijnkoelkast 12 flessen;1;199,95;29-Aug-2018 11:08;03-Sep-2018;;Nieuw;Nee;2qw5e7puvwkwbnhmeim2ixnnlwtf3c@verkopen.bol.com;0634979387;
4895155430;mevrouw;beatrix;de koninck;;langenbergstraat;35;;;2880;BORNEM;Belgi�;mevrouw;beatrix;de koninck;;langenbergstraat;35;;;2880;BORNEM;Belgi�;4016471074750;7475;Easymaxxx Mini Wasmachine;1;76,95;03-Sep-2018 14:09;06-Sep-2018;;Nieuw;Nee;2j5h4bfapn4omvv7lpfywgv6isbumi@verkopen.bol.com;;";
                
                break;
        }
        
        $number = 0;
        
        foreach (explode(PHP_EOL, $csvFile) as $line) {
            $csv = str_getcsv($line, ';');
            $sessionData[$number] = $csv;
            $number ++;
        }
        
        $this->session->set_userdata('salesorderdata', $sessionData);
        $this->session->set_userdata('importformat', $format);
        redirect('import/acceptSalesOrder');
    }
    
    public function dragdropinput()
    {
        if (!isLogged()) {
            redirect('login');
        }
        
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            var_dump($_POST);
            var_dump($_FILES);
            die();
        }
        
        // $this->load->view('test/dragdropinput_standalone_test');
        $this->load->view('test/dragdropinput_global_test');
    }
}
