<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <!-- <span class="close">&times;</span> -->
        <p id="confirmText"></p>
        <div id="confirmButtons">
            <button id="confirmNo">No</button>
            <button id="confirmYes">Yes</button>
        </div>
    </div>
</div>

<script src="../../JS/confirmationModal.js"></script>
<?php
echo '<style>
    #confirmationModal>.modal-content {
        width: fit-content;
        padding: 20px 50px;
        box-sizing: border-box;
        border-radius: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    #confirmText {
        text-align: center;
        margin-bottom: 20px;
    }
    #confirmButtons {
        display: flex;
        justify-content: flex-end;
        width: 100%;
    }
    #confirmYes, #confirmNo {
        margin-left: 10px;
        padding: 5px 10px;
        border-radius: 20px;
    }
    #confirmNo {
        background-color: lightgray;
        color: black;
    }
    #confirmYes {
        background-color: red;
        color: white;
    }
</style>';
?>