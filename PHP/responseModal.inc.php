<div id="responseModal" class="modal">
    <div class="modal-content">
        <!-- <span class="close">&times;</span> -->
        <p id="responseText"></p>
    </div>
</div>

<script src="../../JS/responseModal.js"></script>
<?php echo '<style>
    .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1000;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

    #responseModal>.modal-content {
        width: fit-content;
        padding: 20px 50px;
        box-sizing: border-box;
        border-radius: 30px;
    }
</style>';
