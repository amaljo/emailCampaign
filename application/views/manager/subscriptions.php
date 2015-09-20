 

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Subscriber List
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i><a href="<?= $this->config->base_url(); ?>campaignmanager"> Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-star"> Subscriptions : <?= $clientDetails->clientsDomain ?></i> 
                    </li>

                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subscribed at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cont = 1;
                                foreach ($subscribers as $subscriber):
                                    echo '<tr>
                                    <td scope="row">' . $cont++ . '</td>
                                    <td>' . $subscriber->name . '</td>
                                    <td>' . $subscriber->email . '</td>
                                    <td>' . date('d-m-Y', strtotime($subscriber->created)) . '</td>
                                </tr>';
                                endforeach;
                                ?> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

