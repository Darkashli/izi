<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Systeembeheer');
define('CUSTOMER', $this->uri->segment(3));
define('SUBTITEL', 'Netwerk informatie: ' . getCustomerName($this->uri->segment(3)) . ' (' . $this->uri->segment(3) . ')');
define('PAGE', 'systemmanagement');

define('SUBMENUPAGE', 'network');
define('SUBMENU', $this->load->view('systemmanagement/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
	<?= SUBMENU; ?>
</div>

<form method="post">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<div class="card">
				<div class="card-header card-header-icon card-header-primary">
					<div class="card-icon">
						<i class="material-icons">swap_horiz</i>
					</div>
					<h4 class="card-title">Netwerk informatie</h4>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Netwerkadres</label>
							<input class="form-control" id="ipAddress" name="iprange" value="<?= $systemmanagement->IpRange; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Subnetmasker</label>
							<input class="form-control" id="SubnetMasker" name="subnetmasker" value="<?= $systemmanagement->SubnetMasker; ?>"/>
						</div>

						<div class="col-md-12 col-xs-12 form-group label-floating">
							<label class="control-label">Default gateway</label>
							<input class="form-control" id="DefaultGateway" name="defaultgateway" value="<?= $systemmanagement->DefaultGateway; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Primaire DNS</label>
							<input class="form-control" id="PrimaryDns" name="primarydns" value="<?= $systemmanagement->PrimaryDns; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">Secundaire DNS</label>
							<input class="form-control" id="SecondaryDns" name="secondarydns" value="<?= $systemmanagement->SecondaryDns; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">DNS doorstuurservers</label>
							<input class="form-control" name="dnsforward1" value="<?= $systemmanagement->DnsForward1; ?>" />
							<input class="form-control" name="dnsforward2" value="<?= $systemmanagement->DnsForward2; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">SMTP Server</label>
							<input class="form-control" name="smtpserver1" value="<?= $systemmanagement->SmtpServer1; ?>" />
							<input class="form-control" name="smtpserver2" value="<?= $systemmanagement->SmtpServer2; ?>" />
						</div>

						<div class="clearfix"></div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">DHCP range 1</label>
							<input class="form-control" name="dhcprange10" id="dhcprange10" value="<?= $systemmanagement->DhcpRange10; ?>" />
							<input class="form-control" name="dhcprange11" id="dhcprange11" value="<?= $systemmanagement->DhcpRange11; ?>" />
						</div>

						<div class="col-md-6 col-xs-12 form-group label-floating">
							<label class="control-label">DHCP range 2</label>
							<input class="form-control" name="dhcprange20" id="dhcprange20" value="<?= $systemmanagement->DhcpRange20; ?>" />
							<input class="form-control" name="dhcprange21" id="dhcprange21" value="<?= $systemmanagement->DhcpRange21; ?>" />
						</div>

						<div class="col-12">
							<label class="control-label">Opmerking</label>
							<textarea id="note" name="note" class="editortools" rows="10"><?= $systemmanagement->Note; ?></textarea>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row justify-content-center">
	    <div class="col-md-4">
	        <button class="btn btn-block btn-success" type="submit">Opslaan</button>
	    </div>
	</div>
</form>

<script src="<?= base_url(); ?>assets/js/IpAddress/jquery.input-ip-address-control-1.0.min.js" type="text/javascript"></script>

<script type="text/javascript">
	$(function () {
		$('#ipAddress').ipAddress();
		$('#SubnetMasker').ipAddress();
		$('#DefaultGateway').ipAddress();
		$('#PrimaryDns').ipAddress();
		$('#SecondaryDns').ipAddress();

		$('#dhcprange10').ipAddress();
		$('#dhcprange11').ipAddress();

		$('#dhcprange20').ipAddress();
		$('#dhcprange21').ipAddress();

	});
</script>

<?php include 'application/views/inc/footer.php'; ?>
