<div class="container ">
    <div class="container-fluid">
        <div class="centered col-md-4 col-md-offset-3">
            <?php
            if ($errorLogin != ''):
                echo '<span class="label label-danger">' . $errorLogin . '</span>';
            endif;
            ?>
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">Login to Email Campaign Application</div>
                    <div class="panel-body">
                        <?php
                        echo form_open('');
                        ?> 


                        <div class="form-group">
                            <label>Username</label>
                            <input class="form-control" type="email" required="required" name="userName" placeholder="Email ID">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input class="form-control" required="required"  type="password" name="password" placeholder="Password">
                        </div>


                        <input type="submit" class="btn btn-primary" name="login" value="Login">

                        </form>
                    </div>


                </div>


            </div>
        </div> 
    </div> 
</div>



