 

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
                    <li>
                        <i class="fa fa-signal"></i><a href="<?= $this->config->base_url(); ?>campaignmanager/clients"> Clients</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-star"> Subscriptions : <?= $clientDetails->clientsDomain ?></i> 
                    </li>

                </ol>
            </div>
        </div>
        <!-- /.row -->
        <?php
        if ($errorLogin != ''):
            echo '<span class="alert alert-danger col-lg-12">' . $errorLogin . '</span>';
        endif;
        ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php
                        echo form_open('');
                        echo form_hidden('clientId', $clientId);
                        ?> 
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="subscriberIdsAll"></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subscribed at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cont = 1;
                                foreach ($subscribers as $subscriber):
                                    $rowClass = $subscriber->status == 1 ? 'bg-success' : 'bg-warning';
                                    echo '<tr class="' . $rowClass . '">
                                    <td scope="row" allign="center"><input type="checkbox" class="subscriberIDs" name="subscriberIDs[]" value="' . $subscriber->id . '"></td>
                                    <td>' . $subscriber->name . '</td>
                                    <td>' . $subscriber->email . '</td>
                                    <td>' . date('d-m-Y H:i:s', strtotime($subscriber->created)) . '</td>
                                </tr>';
                                endforeach;
                                ?> 
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">
                            <div class="form-group"  style="overflow: hidden">
                                <label>Action </label><br> 
                                <?php
                                $options = array('' => '--Choose--',
                                    'hold' => 'Keep Hold',
                                    'active' => 'Activate',
                                    'remove' => 'Remove'
                                );
                                echo form_dropdown('action', $options, '', ' class="form-control col-md-4" required="required" style="width:250px;"');
                                ?>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Apply" name="apply" onclick="return confirm('Are you sure?');">   
                            </div>

                            </th>
                            </tr>
                            </tfoot>
                        </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $subscribeForm = form_open('actions/subscribe') . '
                    <input type="hidden" name="clientId" value="' . $clientDetails->id . '">
                    <input type="text" name="name" placeholder="Your Name" required="required">
                    <input type="email" name="email" placeholder="Email Id" required="required">
                    <input type="submit" name="subscribe" value="Subscribe">
                </form>';
        ?>

        <div id="container">
            <h1>Subscribe</h1>

            <div id="body">

                <?php echo $subscribeForm ?>

            </div>

            <p class="footer">To use this form in web pages, place the following code</p>
            <code>
                <?php echo htmlspecialchars($subscribeForm) ?>
            </code>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->


