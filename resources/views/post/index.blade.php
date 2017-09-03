@extends('post.master')
@section('content')
<div id="content">
  @foreach ($post as $item)
  <!-- row -->
  <div class="row">
    <div class="col-sm-12 col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body status">
                <div class="who clearfix">
                    <span class="name"><b>{{$item->name}}</b></span>
                </div>
                <div class="text">
                   {{$item->body}}
               </div>
               <ul class="links">
                <li>
                    <a href="javascript:void(0);" class="like" data-like="1" data-post="{{$item->id}}"><i class="fa fa-thumbs-o-up"></i> Like</a>
                    <span class="badge countlike_{{$item->id}}"  > {{$item->countlike}}</span>
                </li>
                <li>
                    <a href="javascript:void(0);" class="like" data-like="0" data-post="{{$item->id}}"><i class="fa fa-thumbs-o-down"></i> Unlike</a>
                     <span class="badge countunlike_{{$item->id}}" >  {{$item->countdislike}}</span>
                </li>
            </ul>
        </div>
    </div>
</div>
</div>
@endforeach
@endsection
@section('custom_script')
<script type="text/javascript">
$(document).on("click", ".like",function(){
	var id= $(this).data('post');
	
	 $.ajax({
            type: "POST",
            url: '/islike',
            data:{postId: $(this).data('post'),
			isLike: $(this).data('like'),
			_token: '{{ csrf_token() }}',
		},
            success: function( msg ) {
            	var obj = JSON.parse(msg);
            	$('.countlike_'+ id).html(obj.countlike);
            	$('.countunlike_'+id).html(obj.countdislike);
            
            }
        });

});
</script>
@endsection