<!-- Image Zoom In -->
<div id="image-cover-modal" class="image-cover-modal" style="display: none;">
    <img id="image-cover-image" class="image-cover-modal-content">
</div>

<script>
    // $(document).ready(function() {
    //     $('.ZoomInImages').click();
    // });
    let showImageTips = '';
    $('.tips1').on('click', function() {
        showImageTips =  $('.tips1 img').attr('src');
    });

    $('.tips2').on('click', function() {
        showImageTips =  $('.tips2 img').attr('src');
    });

    $('.tips3').on('click', function() {
        showImageTips =  $('.tips3 img').attr('src');
    });

    $('.tips4').on('click', function() {
        showImageTips =  $('.tips4 img').attr('src');
    });

    $('.showTipsContent').on('click', function() {
        $('#image-cover-modal').show();
        // Get the DOM
        var modal = document.getElementById('image-cover-modal');
        var modalImg = document.getElementById("image-cover-image");

        // When the user clicks on <span> (x), close the modal
        modal.onclick = function() {
            this.classList.remove("model-shown");
        }

        $('#image-cover-modal').addClass('model-shown');
        modalImg.src =  showImageTips;//$('.showTipsContent img').attr('src');

    });

</script>
<style>
    .image-cover-modal {
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        position: fixed;
        z-index: 30;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.9);
        transition: opacity ease 0.3s;
        pointer-events: none;
    }

    .model-shown {
        pointer-events: all;
        opacity: 1;
    }

    .image-cover-modal-content {
        display: block;
        max-width: 80%;
        max-height: 80%;
    }

    @media only screen and (max-width: 45rem){
        .image-cover-modal-content {
            max-width: 100%;
            max-height: 100%;
        }
    }
</style>

