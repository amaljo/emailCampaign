 

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Client List
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i><a href="<?= $this->config->base_url(); ?>campaignmanager"> Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-star"> Clients</i> 
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
                                    <th>Domain</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cont = 1;
                                foreach ($clients as $client):
                                    echo '<tr>
                                    <td scope="row">' . $cont++ . '</td>
                                    <td>' . $client->clientsName . '</td>
                                    <td>' . $client->clientsDomain . '</td>
                                    <td><a href="' . $this->config->base_url() . 'campaignmanager/messages/' . $client->id . '">Manage Messages</a></td>
                                        <td><a href="' . $this->config->base_url() . 'campaignmanager/subscriptions/' . $client->id . '">Subscribers</a></td>
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

