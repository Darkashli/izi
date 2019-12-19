<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('TITEL', 'Instellingen');
define('PAGE', 'settings');

define('SUBMENUPAGE', 'users');
define('SUBMENU', $this->load->view('settings/tab', array(), true));

include 'application/views/inc/header.php';
?>

<div class="row">
    <?= SUBMENU; ?>
    <div class="col-auto">
        <a class="btn btn-success float-right" href="<?= base_url('settings/createuser/') ?>">Gebruiker toevoegen</a>
    </div>
</div>

<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class="card">
            <div class="card-header card-header-icon card-header-primary">
                <div class="card-icon">
                    <i class="material-icons">layers</i>
                </div>
                <h4 class="card-title">Klanten</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="userTable" width="100%">
                        <thead>
                            <tr>
                                <td>Gebruikersnaam</td>
                                <td>Voornaam</td>
                                <td>&nbsp;</td>
                                <td>Achternaam</td>
                                <td>E-mail</td>
                                <td>Actief</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user) { ?>
                                <tr data-href="<?= base_url('settings/updateuser/'.$user->Id) ?>">
                                    <td><?= $user->Username; ?></td>
                                    <td><?= $user->FirstName; ?></td>
                                    <td><?= $user->Insertion; ?></td>
                                    <td><?= $user->LastName; ?></td>
                                    <td><?= $user->Email; ?></td>
                                    <td><?= $user->Activated == 1 ? 'Geactiveerd' : 'Gedeactiveerd'; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#userTable').DataTable({
            "language": {
                "url": "/assets/language/Dutch.json"

            }
        });
    });
</script>

<?php include 'application/views/inc/footer.php'; ?>
