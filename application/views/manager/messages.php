 

<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Follow Up Messages
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i><a href="<?= $this->config->base_url(); ?>campaignmanager"> Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-star"> Messages : <?= $clientDetails->clientsDomain ?></i> 
                    </li>
                    <li class=" pull-right">
                        <a class="" href="<?= $this->config->base_url() . 'campaignmanager/saveMessage/' . $clientDetails->id ?>">Add New</a>
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <table class="table table-bordered text-left">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Broadcast time Period(Hrs)</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cont = 1;
                                foreach ($messages as $message):
                                    $rowClass = $message->type == 2 ? 'bg-success' : '';
                                    echo '<tr class="' . $rowClass . '" >
                                    <td scope="row">' . $cont++ . '</td>
                                    <td>' . $message->subject . '</td>
                                    <td>' . $message->timeInterval . '</td>
                                    <td><a href="' . $this->config->base_url() . 'campaignmanager/saveMessage/' . $clientDetails->id . '/' . $message->id . '">Edit</a></td>
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

