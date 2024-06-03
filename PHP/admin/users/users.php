<head>
    <style>
        body {
            padding: 20px;
        }

        .modal-content span.close {
            font-size: 40px;
        }

        .modal-content span.close:hover {
            cursor: pointer;
            color: red;
        }

        .modal-content h2 {
            font-size: 30px;
            color: green;
        }

        .modal-content #freezeReason {
            padding: 20px 40px;
            border-radius: 20px;
        }

        .users h2 {
            font-size: 30px;
            color: green;
        }

        .users h2 span {
            color: gray;
        }

        .users_section {
            padding-top: 20px;
        }

        .user-card {
            padding: 20px;
            margin-bottom: 10px;
            border-radius: 10px;
            /* Add rounded corners */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            font-size: 20px;
        }

        #freezeModal .modal-content button,
        #users-search-bar #users-search-type,
        .user-actions button,
        .user-actions .edit-button,
        .user-actions .user-select {
            color: #eee;
            border: 1px solid;
            border-radius: 20px;
            background: green;
            padding: 5px;
        }

        #users-search-bar button {
            background-color: transparent;
        }

        #users-search-bar button>span {
            font-size: small;
        }

        #freezeModal .modal-content button,
        #users-search-bar #users-search-type,
        #users-search-bar button,
        .user-actions button,
        .user-actions .edit-button,
        .user-actions .user-select:hover {
            cursor: pointer;
        }

        #users-search-bar input {
            padding: 10px;
            border-radius: 20px;
        }

        @media (max-width: 768px) {
            body {
                width: 100%;
            }
        }
    </style>
</head>
<!-- redirected user -->
<div id="freezeModal" class="modal">
    <div class="modal-content">
        <span id="closeModalButton" class="close">&times;</span>
        <h2>Freeze Account</h2>
        <textarea id="freezeReason" placeholder="Account freeze reason..."></textarea>
        <button id="freezeModalSubmit">Save</button>
    </div>
</div>
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span id="closeModalButton" class="close">&times;</span>
        <h2>Edit User Details</h2>
        <form id="editForm">
            <div class="editStatus"></div>
            <input type="hidden" id="editId" class="form-input">
            <input type="text" id="editName" class="form-input" placeholder="Name" required>
            <input type="email" id="editEmail" class="form-input" placeholder="Email" required>
            <input type="tel" id="editPhone" class="form-input" placeholder="Phone" required>
            <input type="text" id="editStreet" class="form-input" placeholder="Street" required>
            <input type="text" id="editCity" class="form-input" placeholder="City" required>
            <button id="updateUserButton" type="submit"><span class="material-symbols-outlined">save</span><span>Save</span></button>
        </form>
    </div>
</div>
<div id="addUserModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span id="closeModalButton" class="close">&times;</span>
        <h2>Add User</h2>
        <p>You can add packing/delivery or admin only. Normal users have to signup instead.</p>
        <form id="addForm">
            <div class="addStatus"></div>
            <input type="text" id="addName" class="form-input" placeholder="Name" required>
            <input type="email" id="addEmail" class="form-input" placeholder="Email" required>
            <input type="tel" id="addPhone" class="form-input" placeholder="Phone" required>
            <input type="password" id="addPassword" class="form-input" placeholder="Password" required>
            <input type="text" id="addStreet" class="form-input" placeholder="Street" required>
            <input type="text" id="addCity" class="form-input" placeholder="City" required>
            <select id="addRole" class="form-input">
                <?php include 'get_user_roles.php'; ?>
            </select>
            <button id="addUserButton" type="submit"><span class="material-symbols-outlined">save</span><span>Save</span></button>
        </form>
    </div>
</div>
<div class="users">
    <h2 class="users_header"><span>Manage</span> Users</h2>
    <div class="top_section">
        <div id="users-search-bar">
            <select id="users-search-type">
                <option value="email">Email</option>
                <option value="account_status">Account Status</option>
                <option value="order_nb">Order#</option>
            </select>
            <input type="text" id="users-search-input" placeholder="Search..."> <button id="user-clear-search"><span class='material-symbols-outlined'>close</span></button>
        </div>
        <button id="addUser"><span class="material-symbols-outlined">add</span><span>Add</span></button>
    </div>
    <section class="users_section">
        <?php
        include_once 'get_users.php';
        foreach ($users as $user) : ?>
            <div class="user-card">
                <div class="user-icon">
                    <span class="material-symbols-outlined">account_circle</span>
                </div>
                <div class="user-info">
                    <h4 class="user-name"><?= $user['name'] ?></h4>
                    <p class="user-email"><?= $user['email'] ?></p>
                    <p class="user-phone"><?= $user['phone'] ?></p>
                    <?php if ($user['account_status'] != 'active') : ?>
                        <p class="user-status"><?= $user['account_status'] ?></p>
                    <?php endif; ?>
                </div>
                <div class="user-actions">

                    <button class='orders-button' data-id="<?= $user['id'] ?>"><span class='material-symbols-outlined'>orders</span><span>Orders</span></button>
                    <button class=" edit-button" data-id="<?= $user['id'] ?>"><span class='material-symbols-outlined'>edit</span></button>
                    <select class="user-select">
                        <option value="active" <?php echo $user['account_status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="freeze" <?php echo $user['account_status'] == 'freeze' ? 'selected' : ''; ?>>Freeze</option>
                    </select>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('#addUserModal .close').click(function() {
            $('#addUserModal').hide();
        });
    });
    // add user modal
    $('#addUser').click(function() {
        $('#addUserModal').show();
    });
    // freeze account modal, enable button when textarea has at least 20 characters
    $(document).ready(function() {
        // Initially disable the button
        $('#freezeModalSubmit').prop('disabled', true);

        // Enable the button only if the textarea is not empty and has at least 20 characters
        $('#freezeReason').on('input', function() {
            var inputText = $(this).val();
            if (inputText.length >= 20) {
                $('#freezeModalSubmit').prop('disabled', false);
            } else {
                $('#freezeModalSubmit').prop('disabled', true);
            }
        });

        // Show the requirement when hovering over the button
        $('#freezeModalSubmit').attr('title', 'You must enter at least 20 characters');
    });
    // user orders redirect
    $(document).on('click', '.orders-button', function() {
        var userId = $(this).data('id');

        function loadContent(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#main-content').html(response);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
        loadContent('users/user_orders.php?user_id=' + userId);
    });
    // user edit modal
    $(document).on('click', '.edit-button', function() {
        var userId = $(this).data('id');
        $('#editId').val(userId);
        $.ajax({
            url: 'users/get_user.php',
            type: 'POST',
            data: {
                userId: userId
            },
            success: function(data) {
                var user = JSON.parse(data);
                $('#editName').val(user.name);
                $('#editEmail').val(user.email);
                $('#editPhone').val(user.phone);
                $('#editStreet').val(user.street_address);
                $('#editCity').val(user.city);

                $('#editModal').show();
            }
        });
    });
    // user edit submit
    $('#updateUserButton').click(function(event) {
        console.log('Form submitted');
        event.preventDefault();

        // Get the updated details
        var userId = $('#editId').val();
        var userName = $('#editName').val();
        var userEmail = $('#editEmail').val();
        var userPhone = $('#editPhone').val();
        var userStreet = $('#editStreet').val();
        var userCity = $('#editCity').val();

        var nameRegex = /^[a-zA-Z\s]+$/;
        var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        var phoneRegex = /^1?[-.\s]?\(?(\d{3})\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;
        var streetRegex = /^[a-zA-Z0-9\s,+-.]+$/;
        var cityRegex = /^[a-zA-Z\s]+$/;

        // Validate the input fields
        if (!nameRegex.test(userName)) {
            $('.editStatus').text('Invalid name. Only letters and spaces are allowed.');
            return;
        }
        if (!emailRegex.test(userEmail)) {
            $('.editStatus').text('Invalid email.');
            return;
        }
        if (!phoneRegex.test(userPhone)) {
            $('.editStatus').text('Phone must match either (XXX) XXX-XXXX or 1-XXX-XXX-XXXX format');
            return;
        }
        if (!streetRegex.test(userStreet)) {
            $('.editStatus').text('Invalid street. Only letters, numbers, spaces, +/- signs and commas are allowed.');
            return;
        }
        if (!cityRegex.test(userCity)) {
            $('.editStatus').text('Invalid city. Only letters and spaces are allowed.');
            return;
        }


        // Send an AJAX request to update the user's details
        $.ajax({
            url: 'users/update_user.php',
            type: 'POST',
            data: {
                id: userId,
                name: userName,
                email: userEmail,
                phone: userPhone,
                street: userStreet,
                city: userCity
            },
            success: function(data) {
                console.log(data);
                $('#editModal').hide();
                showResponseModal('User details updated successfully', function() {
                    $('#users').click();
                });
            }
        });
    });
    // user add submit
    $('#addUserButton').click(function(event) {
        console.log('Form submitted');
        event.preventDefault();

        // Get the updated details
        // var userId = $('#addId').val();
        var adduserName = $('#addName').val();
        var adduserEmail = $('#addEmail').val();
        var adduserPhone = $('#addPhone').val();
        var adduserPass = $('#addPassword').val();
        var adduserStreet = $('#addStreet').val();
        var adduserCity = $('#addCity').val();
        var adduserRole = $('#addRole').val();


        var addnameRegex = /^[a-zA-Z\s]+$/;
        var addemailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        var addphoneRegex = /^1?[-.\s]?\(?(\d{3})\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;
        var addpassRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/; // At least one uppercase letter, one lowercase letter, one digit, and be at least 8 characters long
        var addstreetRegex = /^[a-zA-Z0-9\s,+-.]+$/;
        var addcityRegex = /^[a-zA-Z\s]+$/;

        // Validate the input fields
        if (!addnameRegex.test(adduserName)) {
            $('.addStatus').text('Invalid name. Only letters and spaces are allowed.');
            return;
        }
        if (!addemailRegex.test(adduserEmail)) {
            $('.addStatus').text('Invalid email.');
            return;
        }
        if (!addphoneRegex.test(adduserPhone)) {
            $('.addStatus').text('Phone must match either (XXX) XXX-XXXX or 1-XXX-XXX-XXXX format');
            return;
        }
        if (!addpassRegex.test(adduserPass)) {
            $('.addStatus').text('Password must have 1 upper, 1 lower, 1 digit, min 8 chars');
            return;
        }
        if (!addstreetRegex.test(adduserStreet)) {
            $('.addStatus').text('Invalid street. Only letters, numbers, spaces, +/- signs and commas are allowed.');
            return;
        }
        if (!addcityRegex.test(adduserCity)) {
            $('.addStatus').text('Invalid city. Only letters and spaces are allowed.');
            return;
        }


        // Send an AJAX request to add new user
        $.ajax({
            url: 'users/add_user.php',
            type: 'POST',
            data: {
                name: adduserName,
                email: adduserEmail,
                phone: adduserPhone,
                pass: adduserPass,
                street: adduserStreet,
                city: adduserCity,
                role: adduserRole
            },
            success: function(data) {
                if (data.includes("Email already exists.") || data.includes("Phone already exists.")) {
                    $('.addStatus').text(data);
                } else {
                    console.log(data);
                    $('#addUserModal').hide();
                    showResponseModal('User added successfully', function() {
                        $('#users').click();
                    });
                }
            }
        });
    });
    // auto-format phone input contents based on number format for db contents for phone
    $(document).ready(function() {
        $('#editPhone, #addPhone').on('input', function() {
            var number = $(this).val().replace(/[^\d]/g, '');
            if (number.length > 11) {
                number = number.slice(0, 11);
            }
            if (number.length == 7) {
                number = number.replace(/(\d{3})(\d{4})/, "$1-$2"); // 123-4567 format
            } else if (number.length == 10) {
                number = number.replace(/(\d{3})(\d{3})(\d{4})/, "($1) $2-$3"); // (123) 456-7890 format
            } else if (number.length == 11) {
                number = number.replace(/(\d{1})(\d{3})(\d{3})(\d{4})/, "$1-$2-$3-$4"); // 1-123-456-7890 format
            }
            $(this).val(number);
        });

        $('#editPhone, #addPhone').on('keypress', function(e) {
            if (e.which < 48 || e.which > 57) { //prevent other than number 0-9
                e.preventDefault();
            }
        });
    });
    // user status change
    $(document).on('change', '.user-select', function() {
        var userId = $(this).closest('.user-card').find('.edit-button').data('id');
        var status = $(this).val();
        var freezeReason = ''; // Define freezeReason here

        if (status == 'freeze') {
            $('#freezeModal').show();

            $('#freezeModalSubmit').on('click', function(event) {
                event.stopPropagation(); // Stop the event from propagating up to the select element
                freezeReason = $('#freezeReason').val(); // Update freezeReason here
                $('#freezeModal').hide(); // Hide the modal here

                // Move the AJAX request here
                $.ajax({
                    url: 'users/user_status.php',
                    type: 'POST',
                    data: {
                        userId: userId,
                        status: status,
                        freezeReason: freezeReason
                    },
                    success: function(data) {
                        console.log(data);
                        $('#users').click();
                    }
                });
            });
        } else {
            $.ajax({
                url: 'users/user_status.php',
                type: 'POST',
                data: {
                    userId: userId,
                    status: status
                },
                success: function(data) {
                    console.log(data);
                    $('#users').click();
                }
            });
        }
    });
    // search bar
    $(document).ready(function() {
        $('#user-clear-search').click(function() {
            $('#users-search-input').val('');
            if ($('#users-search-type').val() != 'order_nb') {
                $('#users-search-input').trigger('input');
            } else {
                $('#users-search-type').val('email'); // default search type
                $('#users-search-input').trigger('input');
            }
        });
        $('#users-search-input').on('input', function() {
            var search = $(this).val();
            var search_type = $('#users-search-type').val();
            $.ajax({
                url: 'users/users_search.php',
                type: 'POST',
                data: {
                    search: search,
                    search_type: search_type
                },
                success: function(data) {
                    $('.users_section').html(data);
                }
            });
        });
    });
    // When the user clicks on <span> (x), close the modal
    $('#freezeModal #closeModalButton').click(function() {
        $('.modal').hide();
        $('#user-select').val('active'); // Reset the select element

    });
    $(document).ready(function() {
        $('#editModal .close').click(function() {
            $('#editModal').hide();
        });
    });

    // When the user clicks anywhere outside of the modal, close it
    $(window).click(function(event) {
        if (event.target == $('#freezeModal')[0]) {
            $('#freezeModal').hide();
            $('#user-select').val('active'); // Reset the select element
        }
        if (event.target == $('#editModal')[0]) {
            $('#editModal').hide();
        }
    });
</script>