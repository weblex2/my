<x-blog.blog-layout>
      <div class="py-12 h-full"> 
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 m-5">
                   <form name="createPost" action="{{route("blog.store")}}" method="POST"  class="blog">
                        @csrf
                        <div class="grid grid-cols-12 gap-4">
                            <input type="hidden" name="user_id" value="{{ Auth()->user()->id }}">
                            <div class="col-span-12">Blog: </div>
                            <div class="col-span-12">
                                <select name="category_id">
                                       <option value="1">Laravel</option> 
                                       <option value="2">Projekte</option>
                                </select>     
                            </div> 
                            <div class="col-span-12">Titel: </div>
                            <div class="col-span-12">
                                <input type="text" name="title" value="title">
                            </div>
                            <div class="col-span-12">
                                Content:
                            </div>    
                            <div class="col-span-12">
                               <textarea name="content" id="blog-content">Content goes here..</textarea> 
                            </div>
                            <x-file-upload.fileupload />
 
                   </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        ClassicEditor
        .create( document.querySelector( '#blog-content' ) )
        .catch( error => {
        console.error( error );
        } );
    </script>
</x-blog.blog-layout>
