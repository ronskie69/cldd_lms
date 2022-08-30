<?php

include('../api/functions.php');
require_once('../api/accounts.php');

$account = new AccountsDB();
$event_info = array();

if(isset($_POST['submit']))
{
    // print_r($_POST);
    $event_loc = !empty($_POST['event_location'])  ? $_POST['event_location'] : "N/A";
    $event_receivers = !empty($_POST['event_receivers']) ? $_POST['event_receivers'] : "N/A";
    $event_author = $_SESSION['session_nickname'];
    $event_author_uid = $_SESSION['session_user'];

    $event_info = array(
        "event_title" => ucwords($_POST['event_title']),
        "event_details" => ucfirst($_POST['event_details']),
        "event_date" => $_POST['event_date'],
        "event_author" => $event_author,
        "event_receivers" => $event_receivers,
        "event_location" => $event_loc,
        "event_author_uid" => $event_author_uid
    );
    
    $result = $account->postEvent($event_info);
    $event_info = array();
}

?>


<div class="container">
    <div class="header-events">
        <h4><strong>Events / Announcements</strong></h4>
        <hr/>
    </div>
    <div class="alert alert-warning" role="alert">
        <h5>
            <i class="fa-solid fa-exclamation-triangle me-1"></i>
            This is only available online!
        </h5>
    </div>
    <div class="actions mb-4">
        <button class="btn btn-dark btn-sm p-2" data-bs-toggle="modal" data-bs-target="#modal_new_event">
            <i class="fa-solid fa-add me-2"></i>
            Make Announcement
        </button>
    </div>
    <?php if(!empty($result) && $result['status'] === "error") { ?>
    <div class="alert alert-danger alert-dismissible" role="alert" data-bs-dismiss="alert">
        <div class="d-flex justify-content-between">
            <h5>Failed!</h5>
            <a href="/cldd/loan/index.php?page=coop-events"><i class="fa-solid fa-times" data-bs-dismiss="alert"></i></a>
        </div>
        <p><?php echo $result['message']; ?></p>
    </div>
   <?php } ?>
   <?php if(!empty($result) && $result['status'] === "success") { ?>
    <div class="alert alert-success alert-dismissible" role="alert" data-bs-dismiss="alert">
        <div class="d-flex justify-content-between">
            <h5><?php echo $result['message'] ?></h5>
            <a href="/cldd/loan/index.php?page=coop-events"><i class="fa-solid fa-times" data-bs-dismiss="alert"></i></a>
        </div>
    </div>
   <?php } ?>
    <!-- MODAL CREATE -->
    <div class="modal fade" id="modal_new_event" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <i class="fa-solid fa-calendar text-warning me-2"></i>
                        Make New Event / Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <small>
                        <p class="text-danger">* - Leave if not applicable.</p>
                    </small>
                    <form action="/cldd/loan/index.php?page=coop_events" class="form" method="POST">
                        <div class="form-group mb-2">
                            <label for="event_title">Event Title:</label>
                            <input type="text" class="form-control" name="event_title" required placeholder="Enter your event title here..." required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="event_title">Event Details:</label>
                            <textarea name="event_details" id="event_details" required placeholder= "Enter event details here..." required cols="30" rows="10" class="form-control" name="event_details"></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="event_title">Event Recipients: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="event_receivers" placeholder="This event is for (cooperatives, clients, etc.) ">
                        </div>
                        <div class="form-group mb-2">
                            <label for="event_title">Location of Event: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="event_location" placeholder="Please specify the location of this event.">
                        </div>
                        <div class="form-group mb-2">
                            <label for="event_title">Event Date:</label>
                            <input type="date" class="form-control" name="event_date" placeholder="This event is for (cooperatives, clients, etc.)" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-danger btn-sm" data-bs-dismiss="modal" data-bs-target="#modal_new_event">
                                <i class="fa-solid fa-times me-2"></i>
                                Cancel Event
                            </button>
                            <button type="submit" class="btn btn-primary" name="submit">
                                <i class="fa-solid fa-check me-2"></i>
                                Submit Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm bg-white mb-4">
                <div class="card-header">
                    <i class="fa-solid fa-calendar me-2 text-warning"></i>
                    Events and Announcements
                </div>
                <div class="card-body p-4 card-events">
                    <table class="table shadow-sm" id="events-table">
                        <thead>
                            <tr>
                                <th>Event ID</th>
                                <th>What?</th>
                                <th>Who?</th>
                                <th>When?</th>
                                <th>Where?</th>
                                <th>Posted By</th>
                                <th>Date Posted</th>
                                <!-- <th>Options</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $events = $account->loadEvents();

                            if(!(empty($events))){

                            foreach($events as $event) : ?>
                                <tr>
                                    <td> <?php echo $event['event_id']; ?> </td>
                                    <td> <?php echo $event['event_title']; ?> </td>
                                    <td> <?php echo $event['event_receivers']; ?> </td>
                                    <td> <?php echo $event['event_date']; ?> </td>
                                    <td> <?php echo $event['event_location']; ?> </td>
                                    <td> <?php echo $event['event_author']; ?> </td>
                                    <td> <?php echo $event['date_posted']; ?> </td>
                                    <!-- <td>
                                        <button 
                                            title="View" 
                                            data-bs-toggle = "modal"
                                            data-bs-target="#modal_event_info"
                                            id="view_event" 
                                            data-event-id = "<?php echo $event['event_id']; ?>" 
                                            data-event-uid = "<?php echo $event['event_author_uid'] ?>" 
                                            class="btn btn-info btn-sm btn_view_event">
                                                <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button title="Edit" id="edit_event" data-event-id = "<?php echo $event['event_id']; ?>" data-event-uid = "<?php echo $event['event_author_uid'] ?>" class="btn btn-primary btn-sm btn_edit_event"><i class="fa-solid fa-pen"></i></button>
                                        <button title="Delete" id="delete-event" data-event-id = "<?php echo $event['event_id']; ?>" data-event-uid = "<?php echo $event['event_author_uid'] ?>" class="btn btn-danger btn-sm btn_delete_event"><i class="fa-solid fa-trash"></i></button>
                                    </td> -->
                                </tr>
                            <?php endforeach;
                            } else { ?>
                            <tr>
                                <td>No posted announcement / events yet.</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- MODAL VIEW-->
            <div class="modal fade" id="modal_event_info" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_event_title"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="modal_event_details"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">
                            <i class="fa-solid fa-check me-2"></i>
                            Okay
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#events-table').DataTable();

    $('.btn_view_event').each(function(e){
        $(this).click(function(e){
            // console.log($(this).attr('data-event-id'))
            $.ajax({
                type: 'POST',
                url: '../ajax/loadEventInfo.php',
                data: {
                    event_id: $(this).attr('data-event-id'),
                    event_author_uid: $(this).attr('data-event-uid')
                },
                success: function(data) {
                    data = JSON.parse(data)
                    console.log(data)
                    $('#modal_event_title').text(data.event_title);
                    $('#modal_event_details').text(data.event_details);
                }
            })
        })
    })
</script>