// Init & param dropzone + upload
Dropzone.options.myDropzone = {
  url: "addprod.php",
  autoProcessQueue: false,
  uploadMultiple: false,
  parallelUploads: 1,
  maxFiles: 1,
  maxFilesize: 4,
  acceptedFiles: "image/*",
  addRemoveLinks: true,
  init: function() {
    dzClosure = this; // Makes sure that 'this' is understood inside the functions below.
    
    // for Dropzone to process the queue (instead of default form behavior):
    document
      .getElementById("submit-all")
      .addEventListener("click", function(e) {
        // Make sure that the form isn't actually being sent.
        e.preventDefault();
        e.stopPropagation();
        dzClosure.processQueue();
      });
  }
};
