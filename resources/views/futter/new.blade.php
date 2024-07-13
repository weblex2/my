<x-noppal>
       

Futter! Add
<div class="container mx-auto flex-auto pt-20">
<div id="dropzone">
        <form action="{{ route('futter.save') }}" method="POST" class="dropzone" id="file-upload" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-12">
                <div class="col-span-2 m-1">Name</div>
                <div class="col-span-10 m-1"><input type="text" name="name"></div>

                <div class="col-span-2 m-1">Zutaten</div>
                <div class="col-span-10 m-1"><textarea name="ingredients"></textarea></div>

                <div class="col-span-2 m-1">Zubereitung</div>
                <div class="col-span-10 m-1"><textarea name="howto"></textarea></div>    

                <div class="dz-message col-span-12 bg-zinc-900 text-white border border-zinc-100">
                    Drag and Drop Single/Multiple Files Here<br>
                </div>

                <div class="col-span-12 items-center">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
        var dropzone = new Dropzone('#file-upload', {
            previewTemplate: document.querySelector('#preview-template').innerHTML,
            parallelUploads: 3,
            thumbnailHeight: 150,
            thumbnailWidth: 150,
            maxFilesize: 5,
            filesizeBase: 1500,
            thumbnail: function (file, dataUrl) {
                if (file.previewElement) {
                    file.previewElement.classList.remove("dz-file-preview");
                    var images = file.previewElement.querySelectorAll("[data-dz-thumbnail]");
                    for (var i = 0; i < images.length; i++) {
                        var thumbnailElement = images[i];
                        thumbnailElement.alt = file.name;
                        thumbnailElement.src = dataUrl;
                    }
                    setTimeout(function () {
                        file.previewElement.classList.add("dz-image-preview");
                    }, 1);
                }
            }
        });

        var minSteps = 6,
            maxSteps = 60,
            timeBetweenSteps = 100,
            bytesPerStep = 100000;
        dropzone.uploadFiles = function (files) {
            var self = this;
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                totalSteps = Math.round(Math.min(maxSteps, Math.max(minSteps, file.size / bytesPerStep)));
                for (var step = 0; step < totalSteps; step++) {
                    var duration = timeBetweenSteps * (step + 1);
                    setTimeout(function (file, totalSteps, step) {
                        return function () {
                            file.upload = {
                                progress: 100 * (step + 1) / totalSteps,
                                total: file.size,
                                bytesSent: (step + 1) * file.size / totalSteps
                            };
                            self.emit('uploadprogress', file, file.upload.progress, file.upload
                                .bytesSent);
                            if (file.upload.progress == 100) {
                                file.status = Dropzone.SUCCESS;
                                self.emit("success", file, 'success', null);
                                self.emit("complete", file);
                                self.processQueue();
                            }
                        };
                    }(file, totalSteps, step), duration);
                }
            }
        }
    </script>
</x-noppal>