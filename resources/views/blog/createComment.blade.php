<form id='frmtmp' action='{{route("blog.makeComment")}}' method='POST'>
    @csrf
    <input type="hidden" name="user_id" value="{{ $user_id }}"> 
    <input type="hidden" name="blog_id" value="{{ $id }}"> 
    <textarea name='comment' id="blog-content"></textarea>
    <button class="blog-btn-add-comment" type='button' onclick="saveComment()">Comment</button>
</form>