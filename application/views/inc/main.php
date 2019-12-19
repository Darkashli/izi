<html>

    <head>
        <title><?= TITEL; ?></title>
        <meta http-equiv="content-type" content="application/xhtml+xml;text/html" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW" />
        <meta name="theme-color" content="#306AB2">

        <!-- Jquery -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

        <!-- Popper (required for Bootstrap) -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

        <!-- Bootstrap Material Design (required for Material Dashboard PRO) -->
        <script src="/assets/js/bootstrap-material-design.min.js"></script>

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <?php // NOTE: Bootstrap's javascript files must not be included when using Material Dashboard Pro 2.0 ?>

        <!-- Datatables JS -->
        <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" ></script>
        <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap4.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
        <script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.13/sorting/datetime-moment.js"></script>

        <!-- Bootstrap Switch -->
        <link rel="stylesheet" href="/assets/css/bootstrap-switch.min.css" />
        <script type="text/javascript" src="/assets/js/bootstrap-switch.min.js"></script>

        <script src="/assets/js/bootstrap-notify.js"></script>
        <script src="/assets/js/jquery.tagsinput.js"></script>
        <script src="/assets/js/perfect-scrollbar.jquery.min.js"></script>
        <script src="/assets/js/tinymce/tinymce.min.js"></script>

        <!-- Datetimepicker -->
        <script src="<?= base_url(); ?>assets/js/moment-with-locales.js"></script>
        <script src="<?= base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script>

        <!-- Bootstrap select-picker -->
        <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-select.min.css">
        <script src="<?= base_url() ?>assets/js/bootstrap-select.min.js"></script>

        <!-- Jscolor -->
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jscolor.js"></script>

        <!-- SweetAlert CSS -->
        <link rel="stylesheet" href="/assets/css/sweetalert2.min.css" />
        <script type="text/javascript" src="/assets/js/sweetalert2.all.min.js"></script>

        <!-- Additional fonts -->
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons" />

        <!-- Chartist -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css" />
        <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
        <script type="text/javascript" src="/assets/js/chartist-plugin-tooltip.js"></script>
        <link rel="stylesheet" href="/assets/css/chartist-plugin-tooltip.css" />

        <!-- Fontawesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">

        <!-- Material (required for Material Dashboard Pro) -->
        <script type="text/javascript" src="/assets/js/material.min.js"></script>

        <!-- Material Dashboard PRO -->
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/material-dashboard.min.css" />
        <script src="/assets/js/material-dashboard.min.js"></script>

        <!-- Additional JS files for Material Dashboard wizard -->
        <script src="/assets/js/jquery.bootstrap.wizard.min.js"></script>
        <script src="/assets/js/material-bootstrap-wizard.js"></script>
        <script src="/assets/js/jquery.validate.min.js"></script>
        <script src="/assets/js/jquery.are-you-sure.js"></script>
        <script src="/assets/js/iziWizard.js"></script>



        <!-- Custom style & script -->
        <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/css/style.css" />
        <script src="/assets/js/site.js"></script>

        <!-- For Chrome for Android: -->
        <link rel="icon" sizes="192x192" href="<?= base_url('assets/images/icons/touch-icon-192x192.png'); ?>">

        <!-- For iPhone 6 Plus with @3× display: -->
        <link rel="apple-touch-icon-precomposed" sizes="180x180" href="<?= base_url('assets/images/icons/apple-touch-icon-180x180-precomposed.png'); ?>">
        <!-- For iPad with @2× display running iOS ≥ 7: -->
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?= base_url('assets/images/icons/apple-touch-icon-152x152-precomposed.png'); ?>">
        <!-- For iPad with @2× display running iOS ≤ 6: -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?= base_url('assets/images/icons/apple-touch-icon-144x144-precomposed.png'); ?>">
        <!-- For iPhone with @2× display running iOS ≥ 7: -->
        <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?= base_url('assets/images/icons/apple-touch-icon-120x120-precomposed.png'); ?>">
        <!-- For iPhone with @2× display running iOS ≤ 6: -->
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?= base_url('assets/images/icons/apple-touch-icon-114x114-precomposed.png'); ?>">
        <!-- For the iPad mini and the first- and second-generation iPad (@1× display) on iOS ≥ 7: -->
        <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?= base_url('assets/images/icons/apple-touch-icon-76x76-precomposed.png'); ?>">
        <!-- For the iPad mini and the first- and second-generation iPad (@1× display) on iOS ≤ 6: -->
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?= base_url('assets/images/icons/apple-touch-icon-72x72-precomposed.png'); ?>">
        <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
        <link rel="apple-touch-icon-precomposed" href="<?= base_url('assets/images/icons/apple-touch-icon-precomposed.png'); ?>"><!-- 57×57px -->

        <meta name="apple-mobile-web-app-title" content="izi account" />
        <meta name="mobile-web-app-title" content="izi account" />
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">




        <script type="text/javascript">
            (function (document, navigator, standalone) {
                // prevents links from apps from oppening in mobile safari
                // this javascript must be the first script in your <head>
                if ((standalone in navigator) && navigator[standalone]) {
                    var curnode, location = document.location, stop = /^(a|html)$/i;
                    document.addEventListener('click', function (e) {
                        curnode = e.target;
                        while (!(stop).test(curnode.nodeName)) {
                            curnode = curnode.parentNode;
                        }
                        // Condidions to do this only on links to your own app
                        // if you want all links, use if('href' in curnode) instead.
                        if (
                                'href' in curnode && // is a link
                                (chref = curnode.href).replace(location.href, '').indexOf('#') && // is not an anchor
                                (!(/^[a-z\+\.\-]+:/i).test(chref) || // either does not have a proper scheme (relative links)
                                        chref.indexOf(location.protocol + '//' + location.host) === 0) // or is in the same protocol and domain
                                ) {
                            e.preventDefault();
                            location.href = curnode.href;
                        }
                    }, false);
                }
            })(document, window.navigator, 'standalone');

            $(document).ready(function () {
                $.material.init();

                $('.form-control').on("focus", function () {
                    $(this).parent('.input-group').addClass("input-group-focus");
                }).on("blur", function () {
                    $(this).parent(".input-group").removeClass("input-group-focus");
                });

            });
        </script>

    </head>
    <body class="<?= (get_cookie('sidebar_mini_active') == 'true') ? 'sidebar-mini' : NULL; ?>">
