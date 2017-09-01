@extends('ware.master')
@section('content')
<div id="content">
  @foreach ($ware as $item)
  <!-- row -->
  <div class="row">
    <div class="col-sm-12 col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body status">
                <div class="who clearfix">
                    <span class="name"><b>{{$item->name}}</b></span>
                </div>
                <div class="text">
                   {{$item->article}}
               </div>
               <ul class="links">
                <li>
                    <a href="javascript:void(0);"><i class="fa fa-thumbs-o-up"></i> Like</a>
                    <span class="badge"> 0</span>
                </li>
                <li>
                    <a href="javascript:void(0);"><i class="fa fa-thumbs-o-down"></i> Unlike</a>
                     <span class="badge">  0</span>
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