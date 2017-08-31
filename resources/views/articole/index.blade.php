@extends('articole.master')
@section('content')
<div id="content">
  @foreach ($articol as $item)
  <!-- row -->
  <div class="row">
    <div class="col-sm-12 col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body status">
                <div class="who clearfix">
                    <span class="name"><b>{{$item->name}}</b></span>
                </div>
                <div class="text">
                   {{$item->articol}}
               </div>
               <ul class="links">
                <li>
                    <a href="javascript:void(0);"><i class="fa fa-thumbs-o-up"></i> Like</a>
                    <span class="badge"> {{(empty($item->like) ? 0 : $item->like)}}</span>
                </li>
                <li>
                    <a href="javascript:void(0);"><i class="fa fa-thumbs-o-down"></i> Unlike</a>
                     <span class="badge">  {{(empty($item->unlike) ? 0 : $item->unlike)}}</span>
                </li>
            </ul>
        </div>
    </div>
</div>
</div>
@endforeach
@endsection

@section('custom_script')

@endsection