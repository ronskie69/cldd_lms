<!-- MODAL -->
<div class="modal fade" id="modal_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Active User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="/cldd/loan/index.php?page=active">
                <div class="mb-3">
                    <label for="fname" class="col-form-label">First Name:</label>
                    <input type="text" class="form-control" id="fname" name="fname" required placeholder="Enter first name here...">
                </div>
                <div class="mb-3">
                    <label for="lname" class="col-form-label">Last Name:</label>
                    <input type="text" class="form-control" id="lname" name="lname" required placeholder="Enter last name here...">
                </div>
                <div class="mb-3">
                    <label for="dob" class="col-form-label">Date of Birth:</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <div class="mb-3">
                    <label for="dob" class="col-form-label">Contact Number:</label>
                    <input type="text" placeholder="Enter contact number here..." required class="form-control" id="contact_no" name="contact_no">
                </div>
                <div class="mb-3">
                    <label for="address" class="col-form-label">Address:</label>
                    <select class="form-select form-select-lg mb-3" aria-label="address" required name="address">
                        <option value="Barangay 1">Barangay 1</option>
                        <option value="Barangay 2">Barangay 2</option>
                        <option value="Barangay 2">Barangay 3</option>
                        <option value="Barangay 4">Barangay 4</option>
                        <option value="Barangay 5">Barangay 5</option>
                        <option value="Barangay 6">Barangay 6</option>
                        <option value="Barangay 7">Barangay 7</option>
                        <option value="Bagong Kalsada">Bagong Kalsada</option>
                        <option value="Bañadero">Bañadero</option>
                        <option value="Banlic">Banlic</option>
                        <option value="Barandal" selected>Barandal</option>
                        <option value="Batino">Batino</option>
                        <option value="Bubuyan">Bubuyan</option>
                        <option value="Bucal">Bucal</option>
                        <option value="Bunggo">Bunggo</option>
                        <option value="Burol">Burol</option>
                        <option value="Camaligan">Camaligan</option>
                        <option value="Canlubang">Canlubang</option>
                        <option value="Halang">Halang</option>
                        <option value="Hornalan">Hornalan</option>
                        <option value="Kay-Anlog">Kay-Anlog</option>
                        <option value="Laguerta">Laguerta</option>
                        <option value="La Mesa">La Mesa</option>
                        <option value="Lawa">Lawa</option>
                        <option value="Lecheria">Lecheria</option>
                        <option value="Lingga">Lingga</option>
                        <option value="Looc">Looc</option>
                        <option value="Mabato">Mabato</option>
                        <option value="Majada-Labas">Majada-Labas</option>
                        <option value="Makiling">Makiling</option>
                        <option value="Mapagong">Mapagong</option>
                        <option value="Masili">Masili</option>
                        <option value="Maunong">Maunong</option>
                        <option value="Mayapa">Mayapa</option>
                        <option value="Milagrosa">Milagrosa</option>
                        <option value="Paciano Rizal">Paciano Rizal</option>
                        <option value="Parian">Parian</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <input type="button" data-bs-dismiss="modal" class="btn btn-danger" value="Cancel">
                    <input type="submit" class="btn btn-success" value="Add Client">
                </div>
            </form>
        </div>
        </div>
    </div>
    </div>