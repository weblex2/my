<div class="col-span-12">
    <form id="fileUploadForm" method="POST" action="{{ url('/upload') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3">
            <input name="file" type="file" class="form-control">
        </div>
        <div class="d-grid mb-3">
            <input type="submit" value="Submit" class="btn btn-primary">
        </div>
        <div class="form-group">
            <div class="progress">
                <div class="bg-red-500 h-3 w-10" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
        </div>
    </form>
</div>