 

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
                    <li>
                        <i class="fa fa-signal"></i><a href="<?= $this->config->base_url(); ?>campaignmanager/clients"> Clients</a>
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

        <?php
        if ($warning != ''):
            echo '<span class="alert alert-warning col-lg-12">' . $warning . '</span>';
        endif;
        ?>

        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <table class="table table-bordered text-left table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Subject</th>
                                    <th>Broadcast time Period(Hrs)</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cont = 1;

                                foreach ($messages as $message):
                                    switch ($message->type) {
                                        case 1:
                                            $rowClass = 'bg-warning';
                                            $extra = '';
                                            break;
                                        case 2:
                                            $rowClass = 'bg-success';
                                            $extra = '';
                                            break;
                                        case 3:
                                            $rowClass = 'bg-info';
                                            $extra = ' <a onclick="return confirm(\'Are you ready to send this message to all subscribers?\')" class="label btn-info" href="' . $this->config->base_url() . 'actions/suiteUpBroadcast/' . $clientDetails->id . '/' . $message->type . '/' . $message->id . '/admin' . '">Send Now</a>';
                                            break;

                                        default:
                                            break;
                                    }

                                    echo '<tr class="' . $rowClass . '" >
                                    <td scope="row">' . $cont++ . '</td>
                                    <td>(' . $options[$message->type] . ') ' . $message->subject . $extra . '</td>
                                    <td>' . $message->timeInterval . '</td>
                                    <td><a href="' . $this->config->base_url() . 'campaignmanager/saveMessage/' . $clientDetails->id . '/' . $message->id . '">Edit</a></td>
                                        <td><a href="' . $this->config->base_url() . 'campaignmanager/remove/' . $message->id . '" onclick="return confirm(\'Are you sure?\');">Remove</a></td>
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

