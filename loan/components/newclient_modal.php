<!-- MODAL ADD NEW CLIENT -->
<div class="modal fade" id="modal_addnew" data-bs-backdrop="static" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                <i class="fa-solid fa-user-plus me-1"></i>
                New Active User
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h6>
                <span class="text-danger">* - Required details.</span>
            </h6>
            <form method="POST" class="form" enctype="multipart/form-data" action="/cldd/loan/index.php?page=active">
            <div class="mb-2">
                    <label for="fname" class="col-form-label">First Name: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="fname" name="fname" required placeholder="Enter first name here...">
                </div>
                <div class="mb-2">
                    <label for="lname" class="col-form-label">Last Name: <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="lname" name="lname" required placeholder="Enter last name here...">
                </div>
                <div class="mb-2">
                    <label for="dob" class="col-form-label">Date of Birth: <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <div class="mb-2">
                    <label for="gender" class="col-form-label">Gender at Birth: <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg mb-2" aria-label="gender" required name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label for="dob" class="col-form-label">Contact Number: <span class="text-danger">*</span></label>
                    <input type="text" placeholder="Enter contact number here..." required class="form-control" id="contact_no" name="contact_no">
                </div>
                <div class="mb-2">
                    <label for="address" class="col-form-label">Address: <span class="text-danger">*</span></label>
                    <select class="form-select form-select-lg mb-2" aria-label="address" required name="address">
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
                        <option value="Prinza">Prinza</option>
                        <option value="Puting Lupa">Puting Lupa</option>
                        <option value="Real">Real</option>
                        <option value="Saimsim">Saimsim</option>
                        <option value="Sampiruhan">Sampiruhan</option>
                        <option value="San Cristobal">San Cristobal</option>
                        <option value="San Jose">San Jose</option>
                        <option value="San Juan">San Juan</option>
                        <option value="Sucol">Sucol</option>
                        <option value="Turbina">Turbina</option>
                        <option value="Ulango">Ulango</option>
                        <option value="Uwisan">Uwisan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fname" class="col-form-label">Client's Profile Image: <span class="text-danger">*</span></label>
                    <input type="file" accept="image/*" class="form-control" id="image" name="image" required>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" data-bs-dismiss="modal" class="btn btn-danger">
                        <i class="fa-solid fa-times me-1"></i>
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success" name="submit">
                        <i class="fa-solid fa-check me-1"></i>
                        Save and Add
                    </button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>