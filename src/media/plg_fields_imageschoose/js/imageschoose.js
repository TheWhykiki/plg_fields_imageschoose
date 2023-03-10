document.addEventListener("DOMContentLoaded", function () {

    // Modal Image Choose
    let imageChooseModalButtons = document.querySelectorAll(".imageChooseModalButton");

    imageChooseModalButtons.forEach(function (button) {
        let buttonTargetId = button.dataset.bsTarget;
        let fieldId = button.dataset.bsId;
        let modalBox = document.querySelector(buttonTargetId);
        let addImagesButton = modalBox.querySelector(".addImages");
        let iframe = modalBox.querySelector("iframe");

        setPreview(fieldId);

        addImagesButton.addEventListener("click", function (e) {
            e.preventDefault();

            let selectedImages = iframe.contentDocument.querySelectorAll("li.selected");

            let imagesLi = "";
            let imagesArray = [];

            selectedImages.forEach(function (image, index) {
                imagesArray.push(image.dataset.url);

                let data = image.dataset.url;

                imagesLi += '<li data-listid="' + (index + 1) + '" data-imagesource="' + data + '" draggable="true"><img src="/' + data + '" />';
                imagesLi += '<button type="button" class="remove-btn"><span class="icon-remove"></span></button></li>';

            });

            let images = imagesArray.join(",");

            document.querySelector("#" + fieldId).value = images;
            document.querySelector("#previewList-" + fieldId).innerHTML = imagesLi;

            let modalWindow = bootstrap.Modal.getInstance(modalBox)
            modalWindow.hide();
            myDragDrop(fieldId);
        });
        myDragDrop(fieldId);
    });

    function myDragDrop(fieldId)
    {
        // Results DragDrop

        // Get the UL and all the LI elements
        let ul = document.querySelector('#previewList-' + fieldId);
        let lis = ul.querySelectorAll('li');

        // Add drag and drop functionality to the LI elements
        for (let i = 0; i < lis.length; i++) {
            let li = lis[i];
            li.draggable = true;

            li.addEventListener('dragstart', function(e) {
                e.dataTransfer.setData('text/plain', this.id);
                this.classList.add('dragging');
            });

            li.addEventListener('dragend', function(e) {
                this.classList.remove('dragging');
                updateImageOrder(fieldId);
            });
        }

        // Add remove button functionality to the LI elements

        let removeButtons = ul.querySelectorAll('.remove-btn');
        for (let i = 0; i < removeButtons.length; i++) {
            let removeButton = removeButtons[i];

            removeButton.addEventListener('click', function() {
                this.parentNode.remove();
                updateImageOrder(fieldId);
            });
        }

        lis.forEach(item => {
            item.addEventListener('dragstart', dragStart);
            item.addEventListener('dragover', dragOver);
            item.addEventListener('drop', function(event){drop(event, fieldId)});
            item.addEventListener('dragend', dragEnd);
        });
    }

    function setPreview(fieldId) {
        var imagesLi = "";
        var previewImages = document.querySelector("#" + fieldId).value;

        previewImages = previewImages.split(",");
        previewImages.map(function (data, index) {
            imagesLi += '<li data-listid="' + (index + 1) + '" data-imagesource="' + data + '" draggable="true"><img src="/' + data + '" />';
            imagesLi += '<button type="button" class="remove-btn"><span class="iconTrash"></span></button></li>';
        });

        document.querySelector("#previewList-" + fieldId).innerHTML = imagesLi;
    }

    // Update the hidden input with the current image order
    function updateImageOrder(fieldId) {
        let ul = document.querySelector('#previewList-' + fieldId);
        let lis = ul.querySelectorAll('li');

        let newOrder = [];
        for (let i = 0; i < lis.length; i++) {
            let li = lis[i];
            newOrder.push(li.getAttribute('data-imagesource'));
            li.setAttribute('data-listid', i + 1);
        }

        console.log('newOrder', newOrder);
        document.getElementById(fieldId).value = newOrder.join(',');
    }

    function dragStart(event) {
        let target = event.target;
        event.dataTransfer.setData('text/plain', target.dataset.listid);
        target.classList.add('dragging');
    }

    function dragOver(event) {
        event.preventDefault();
        let target = event.target;
        target.classList.add('dragover');
    }

    function drop(event, fieldId) {

        event.preventDefault();
        let target = event.target;
        let id = event.dataTransfer.getData('text/plain');

        let ul = document.querySelector('#previewList-' + fieldId);

        let draggableElement = ul.querySelector('[data-listid="' + id + '"]');
        let dropzone = target.closest('li');

        if (!dropzone) return;
        let dropzoneId = dropzone.getAttribute('data-listid');
        if (target.classList.contains('remove-btn')) {
            // Handle remove button drop
            // ...
        } else if (draggableElement && dropzoneId !== id) {
            // Handle drop reordering
            let draggableIndex = [...draggableElement.parentNode.children].indexOf(draggableElement);
            let dropzoneIndex = [...dropzone.parentNode.children].indexOf(dropzone);
            if (draggableIndex < dropzoneIndex) {
                dropzone.parentNode.insertBefore(draggableElement, dropzone.nextElementSibling);
            } else {
                dropzone.parentNode.insertBefore(draggableElement, dropzone);
            }
            updateImageOrder(fieldId);
        }
        target.classList.remove('dragover');
    }

    function dragEnd(event) {
        let target = event.target;
        target.classList.remove('dragging');
    }
});

