<x-blog.blog-layout>
       <div class="py-12 h-full">
        <div class="w-7/8 mx-auto sm:px-6 lg:px-8 p-3 h-full">
            <div class="overflow-hidden sm:rounded-lg h-full">
                <div class="p-3 m-5">
                   <form name="createPost" action="{{route("ntfy.store")}}" method="POST"  class="blog">
                        @csrf
                        <div class="grid grid-cols-12 gap-4">
                            <input type="hidden" name="user_id" value="{{ isset(Auth()->user()->id) ? Auth()->user()->id :'' }}">
                            
                            <div class="col-span-12">
                                Topic:
                            </div>    
                            <div class="col-span-12">
                               <input type="text" id="ntfy-topic" name="topic"> 
                            </div>

                            <div class="col-span-12">
                                Description:
                            </div>    
                            <div class="col-span-12">
                               <input type="text" id="ntfy-topic" name="description"> 
                            </div>

                            <div class="col-span-4">
                                Date:
                            </div>    
                            <div class="col-span-4">
                                Reminder:
                            </div> 

                            <div class="col-span-4">
                                Reoccurance:
                            </div> 

                            <div class="col-span-4">
                               <input type="datetime-local" id="ntfy-date" name="date"> 
                            </div>
  
                            <div class="col-span-4">
                               <input type="datetime-local" id="ntfy-date" name="reminder"> 
                            </div>

                            <div class="col-span-4">
                               <select name="recurring_interval">
                                    <option value="">none</option>
                                    <option value="D">Day</option>
                                    <option value="M">Month</option>
                                    <option value="Y">Year</option>
                                    <option value="H">Hour</option>
                                    <option value="M">Minute</option>
                               </select>
                            </div>
                            <input type="submit" name="Save" class="btn"> 
                   </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#ntfy-date').datetimepicker();
    </script>
</x-blog.blog-layout>
