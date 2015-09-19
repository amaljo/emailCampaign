 

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
                        <i class="fa fa-th"></i><a href="<?= $this->config->base_url(); ?>campaignmanager/messages/<?= $clientDetails->id ?>"> <?= $clientDetails->clientsDomain ?></a>
                    </li>
                    <li class="active">
                        <i class="fa fa-star"> Message</i> 
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12 text-center">                
                <?php
                if ($errorLogin != ''):
                    echo '<span class="label label-danger">' . $errorLogin . '</span>';
                endif;
                ?>
                <div class="row text-left">
                    <div class="panel panel-default">
                        <div class="panel-heading">Complete the following form</div>
                        <div class="panel-body">
                            <?php
                            echo form_open('');
                            echo form_hidden('keepTags', 'TRUE');
                            echo form_hidden('clientId', $clientId);
                            echo form_hidden('id', $id);
                            ?> 


                            <div class="form-group">
                                <label>Subject</label>
                                <input class="form-control" type="text" required="required" name="subject" placeholder="Subject" value="<?= $subject ?>">
                            </div>

                            <div class="form-group">
                                <label>Message</label>
                                <?php
                                $data = array('id' => 'message', 'name' => 'message', 'rows' => '15', 'cols' => '30', 'value' => $message, 'class' => 'form-control span10 tinyMCE');
                                echo form_textarea($data);
                                ?>

                            </div>

                            <div class="form-group">
                                <label>Broadcast time Period (Hrs)</label><br>
                                <input class="form-control col-md-2" size="10" type="text" required="required" name="timeInterval" placeholder="Hours" style="width: 100px;" value="<?= $timeInterval ?>">
                                <br>
                            </div>

                            <div class="form-group">
                                <label>Type</label>                                       
                                <?php
                                $options = array(1 => 'Follow Up',
                                    2 => 'Welcome Message'
                                );
                                echo form_dropdown('type', $options, set_value('type', $type), array('class' => 'form-control'));
                                ?>
                            </div>

                            <input type="submit" class="btn btn-primary" name="save" value="Save">

                            </form>
                        </div>


                    </div>


                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

