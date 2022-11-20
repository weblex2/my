<x-blog.blog-layout>
    <div class="py-12 h-full">
     <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
         <div class="overflow-hidden sm:rounded-lg h-full">
             <div class="p-3 m-5">
                <div class="blog">
                <h1>Content of Jobs table:</h1>
                <table class="text-white bg-slate-700">
                    <tr>
                        @foreach ($jobs[0]->getAttributes() as $field => $value) 
                            <td class="p-2 border border-slate-500">{{ strtoupper($field) }}</td>
                            @endforeach  
                    </tr>    
                    @foreach ($jobs as $job)
                        <tr>
                            @foreach ($job->getAttributes() as $field => $value) 
                            <td class="p-2 border border-slate-500">{{ $value }}</td>
                            @endforeach          
                        </tr>
                    @endforeach
                </table> 
                </div>
             </div>
         </div>
     </div>
 </div>
</x-blog.blog-layout>

   