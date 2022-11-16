<x-blog.blog-layout>
    <div class="container " style="max-width: 500px">
        <div class="pt-10 alert alert-warning mb-4 text-center">
           <h2 class="display-6">Laravel Dynamic Ajax Progress Bar Example</h2>
        </div>  
        <form id="fileUploadForm" method="POST" action="{{ url('/upload') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <input name="file" type="file" class="form-control"> Upload File
            </div>
            <div class="d-grid mb-3">
                <input type="submit" value="Submit" class="btn btn-primary">
            </div>
            <div class="form-group bg-slate-800 w-full">
               
                <div class="progress h-3 w-full bg-slate-100 rounded-xl">
                    <div class="progress progress-bar h-3 bg-green-700 rounded-xl" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
            </div>
        </form>
    </div>
   
    <script>
        $(function () {
            $(document).ready(function () {
                $('#fileUploadForm').ajaxForm({
                    beforeSend: function () {
                        var percentage = '0';
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('.progress .progress-bar').css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function (xhr) {
                        console.log('File has uploaded');
                    }
                });
            });
        });
    </script>
</x-blog.blog-layout>
