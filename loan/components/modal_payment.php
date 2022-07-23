<div class="modal fade" data-bs-backdrop="static" id="make_payment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    <i class="fa-solid fa-peso-sign me-2 text-success"></i>
                    Make Payment
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-holder-payment p-4">
                    <form action="/cldd/loan/index.php?page=payments" class="form" id="form_payment" method="POST">
                        <label for="client_name">Payment By:</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" required placeholder="Enter last name here..." name="client_name" id="client_name">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa-solid fa-user"></i>
                            </span>
                        </div>
                        <label for="fname">Amount of Payment:</label>
                        <div class="input-group mb-3">
                            <input type="number" class="form-control" required placeholder="Enter amount of payment here..." name="payment_amount" id="username">
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa-solid fa-money-bill"></i>
                            </span>
                        </div>
                        <label for="fname">Payment Type:</label>
                        <div class="input-group mb-3">
                            <select name="payment_type" id="payment_type" class="form-control">
                                    <option value="Current" selected>Current</option>
                                    <option value="Due">Due</option>
                            </select>
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa-solid fa-hands"></i>
                            </span>
                        </div>
                        <label for="fname">Mode of Payment:</label>
                        <div class="input-group mb-3">
                            <select name="mode_of_payment" id="mode_of_payment" class="form-control">
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Actual Payment">Actual Payment</option>
                            </select>
                            <span class="input-group-text" id="basic-addon1">
                                <i class="fa-solid fa-desktop"></i>
                            </span>
                        </div>
                        <label for="fname">Date of Payment:</label>
                        <div class="input-group mb-3">
                            <input type="date" name="payment_date" id="payment_date" class="form-control"/>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">
                                <i class="fa-solid fa-times me-2"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-success" name="submit_payment">
                                <i class="fa-solid fa-check me-2"></i>
                                Submit Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>