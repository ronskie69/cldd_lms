<?php

include('../api/functions.php');
require_once('../api/clients.php');

$clients = new DBClients();
$result = array();

if(isset($_POST['confirm-activate']))
{
    $result = $clients->setClientAccountType($_POST['client_uid'], $_POST['account_type']);
    unset($_POST);
}
if(isset($_POST['confirm-delete']))
{
    $result = $clients->deleteClient($_POST['client_uid']);
    unset($_POST);
}
?>

<div class="container">


<!-- ALERT FOR ACTIVATE/CLOSE ACCOUNT -->
<?php if(isset($_GET['close_account'])) { ?>
    <div class="alert alert-warning" role="alert">
        <h5><i class="fa-solid fa-exclamation-triangle me-2"></i>Are you sure to set account of <?php echo $_GET['client_name'];?> to Active?</h5>

        <form class="form mt-3" method="POST" action="/cldd/loan/index.php?page=closed">
            <input type="hidden" name ="account_type" value ="active">
            <input type="hidden" name ="client_uid" value ="<?php echo $_GET['close_account'];?>">
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="alert">
                    <i class="fa-solid fa-times me-2"></i>
                    No
                </button>
                <button name="confirm-activate" type="submit" class="btn btn-success btn-sm">
                    <i class="fa-solid fa-check me-2"></i>
                    Yes
                </button>
            </div>
        </form>
    </div>
   <?php } ?>

   <!-- ALERT FOR DELETE -->
    <?php if(isset($_GET['delete_account'])) { ?>
        <div class="alert alert-warning" role="alert">
            <h5><i class="fa-solid fa-exclamation-triangle"></i> Are you sure to delete <?php echo $_GET['client_name'];?> forever!</h5>
            <p>There is no going back.</p>

            <form class="form mt-3" method="POST" action="/cldd/loan/index.php?page=closed">
                <input type="hidden" name ="client_uid" value ="<?php echo $_GET['delete_account'];?>">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-success btn-sm" data-bs-dismiss="alert">
                        <i class="fa-solid fa-times me-2"></i>
                        No
                    </button>
                    <button name="confirm-delete" type="submit" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-exclamation-triangle me-2"></i>
                        Continue Deletion
                    </button>
                </div>
            </form>
        </div>
    <?php } ?>

    <?php if(!empty($result) && $result['status'] === "error") { ?>
    <div class="alert alert-danger alert-dismissible" role="alert" data-bs-dismiss="alert">
        <h5>Error!</h5>
        <p><?php echo $result['message']; ?></p>
    </div>

   <?php } ?>
   <?php if(!empty($result) && $result['status'] === "success") { ?>
    <div class="alert alert-success alert-dismissible" role="alert" data-bs-dismiss="alert">
        <h5><?php echo $result['message'] ?></h5>
    </div>
   <?php } ?>


    <h4 class="text-center"><strong>Inactive / Closed Accounts</strong></h4>
    <hr/>
    <div class="col-12">
            <table class="table shadow-sm" id="users-table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Date of Birth</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>
                            Options
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php

                $type = isset($_GET['page']) ? $_GET['page'] : "closed";

                $data = $clients->getClientInfos($type);

                if(!empty($data)) {

                foreach($data as $datax) : ?>
                    <tr>
                        <td> <?php echo $datax['client_user_id']; ?> </td>
                        <td> <?php echo $datax['fname']; ?> </td>
                        <td> <?php echo $datax['lname']; ?> </td>
                        <td> <?php echo $datax['dob']; ?> </td>
                        <td> <?php echo $datax['address']; ?> </td>
                        <td> <?php echo ucwords($datax['account_type']); ?> </td>
                        <td>
                            <a title ="Delete" href="/cldd/loan/index.php?page=closed&delete_account=<?php echo $datax['client_user_id']?>&client_name=<?php echo formatName($datax['fname'], $datax['lname']); ?>" class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                            <a title ="Activate Account" href="/cldd/loan/index.php?page=closed&close_account=<?php echo $datax['client_user_id']?>&client_name=<?php echo formatName($datax['fname'], $datax['lname']); ?>" class="btn btn-dark btn-sm">
                                <i class="fa-solid fa-folder-closed"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach;
                } else { ?>
                <tr>
                    <td class="text-center" colspan="7">There is no existing closed/inactive accounts.</td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#users-table').DataTable();

    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>