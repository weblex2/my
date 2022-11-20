<x-blog.blog-layout>
    <div class="py-12 h-full">
     <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
         <div class="overflow-hidden sm:rounded-lg h-full">
             <div class="p-3 m-5">
                <form id="frmCreateJob" action="{{route('job.createJob')}}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>Subject</div> 
                        <div><input type="text" name="subject"></div>
                        <div>Body</div> 
                        <div><textarea  name="body"></textarea></div>
                    </div>
                    <button type="submit">Submit</button>
                </form>     
             </div>
         </div>
     </div>
 </div>
</x-blog.blog-layout>

   