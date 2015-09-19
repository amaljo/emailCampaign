<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Welcome Admin</title>

        <!-- Bootstrap Core CSS -->
        <link href="<?= $this->config->base_url(); ?>/assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom CSS -->
        <link href="<?= $this->config->base_url(); ?>/assets/css/sb-admin.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link href="<?= $this->config->base_url(); ?>/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        

    </head>

    <body>

        <?php if ($this->session->userdata('logged_in')) : ?>
            <div id="wrapper">


                <!-- Navigation -->
                <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="<?= $this->config->base_url(); ?>campaignmanager">SB Admin</a>
                    </div>
                    <!-- Top Menu Items -->
                    <ul class="nav navbar-right top-nav">

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Hello <?= $this->session->userdata('name') ?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">

                                <li>
                                    <a href="<?= $this->config->base_url(); ?>campaignmanager/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
                    <div class="collapse navbar-collapse navbar-ex1-collapse">
                        <ul class="nav navbar-nav side-nav">
                            <li>
                                <a href="<?= $this->config->base_url(); ?>campaignmanager"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                            </li>
                            <li>
                                <a href="<?= $this->config->base_url(); ?>campaignmanager/clients"><i class="fa fa-fw fa-bar-chart-o"></i> Clients</a>
                            </li>

                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
                </nav>

            <?php endif; ?>