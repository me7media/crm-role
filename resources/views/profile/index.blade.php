@extends('layouts.app')

@if(!$year) $year = date('Y') @endif
@if(!$month) $month = date('m') @endif
@php $mon = array("01"=>"Январь","02"=>"Февраль","03"=>"Март","04"=>"Апрель","05"=>"Май", "06"=>"Июнь", "07"=>"Июль","08"=>"Август","09"=>"Сентябрь","10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь"); 
$rusmonth = $mon[$month];
@endphp

@section('title', date('F Y', mktime(0,0,0,$month ,1,$year)).'  -  '.$rusmonth.' '.$year)

@section('content')
<meta name="_token" content="{{ csrf_token() }}" /> 
<link href="{{ asset('public/css/datepicker.css')}}" rel="stylesheet" type="text/css">
<div class="col-lg-10 col-lg-offset-1">
    <h2><i class="fa fa-users"></i> User Data (Click on table to edit data!)
   </h2>
   <form method="get">
<div class="input-group pull-left">
  <select name="month" class="">
      <option value="01" @if($month=='01') selected @endif >Jan</option>
      <option value="02" @if($month=='02') selected @endif >Feb</option>
      <option value="03" @if($month=='03') selected @endif >Mar</option>
      <option value="04" @if($month=='04') selected @endif >Apr</option>
      <option value="05" @if($month=='05') selected @endif >May</option>
      <option value="06" @if($month=='06') selected @endif >Jun</option>
      <option value="07" @if($month=='07') selected @endif >Jul</option>
      <option value="08" @if($month=='08') selected @endif >Aug</option>
      <option value="09" @if($month=='09') selected @endif >Sep</option>
      <option value="10" @if($month=='10') selected @endif >Oct</option>
      <option value="11" @if($month=='11') selected @endif >Nov</option>
      <option value="12" @if($month=='12') selected @endif >Dec</option>
  </select>
    <select name="year" class="">
      <option value="2017" @if($year=='2017') selected @endif >2017</option>
      <option value="2018" @if($year=='2018') selected @endif>2018</option>
  </select>
  <button type="submit" class="btn btn-xs">Change</button>
  </div>
</form>
@role('admin')
@else
<a href="#" data-hover="tooltip" data-placement="top" data-target="#modal-create" data-toggle="modal"  title="Add" class="btn btn-success pull-right btn-xs">ADD NEW</a>
@endrole
    <hr>
        @if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
        @if($datas->isEmpty())
       <h1 class="btn btn-warning"> NO DATA WAS FOUND </h1>
        @else
    <div class="table-responsive">

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Comments</th>
                    <th width="50px">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($datas as $data)
                <tr>

                    <td class="edit" id="day" title="Click to Edit">{{ $data->day }}</td>
                    <td class="edit" id="time" title="Click to Edit">{{ $data->time }}</td>
                    <td class="edit" id="com" title="Click to Edit">{{ $data->com }}</td>
                    <td>
        {!! Form::model( array('route' => array('profile.update', $data->id), 'method' => 'PUT')) !!}
                    <input type="hidden" id="day" name="day" value="{{ $data->day }}"/>
                    <input type="hidden" id="time" name="time" value="{{ $data->time }}"/>
                    <input type="hidden" id="com" name="com" value="{{ $data->com }}"/>
                    <input type="hidden" id="hash" name="hash" value="{{ $data->hash }}" />
                    {!! Form::submit('Save', ['class' => 'btn btn-info btn-xs pull-left', 'style'=>'margin-bottom:5px;']) !!}
        {!! Form::close() !!}

                    {!! Form::open(['method' => 'DELETE', 'route' => ['profile.destroy', $data->id] ]) !!}
                    <input type="hidden" id="hash" name="hash" value="{{ $data->hash }}" />
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs pull-right']) !!}
                    {!! Form::close() !!}
                    </td>
                    
                </tr>
                @endforeach
                @endif
            </tbody>

        </table>
    </div>
</div>

<div class="modal fade" id="modal-create" tabindex="8" role="dialog" aria-labelledby="form-modal" aria-hidden="true">
<div class="modal-dialog">
           <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">[X]</span></button>
                <h4 class="modal-title" id="myModalLabel">Add new data</h4>
            </div>
            <div class="modal-body">
                {{-- @include ('errors.list') --}}

                {{ Form::open(array('url' => 'profile')) }}
                <div class="form-group">
                 <label for="inputDay" class="col-sm-3 control-label">Day</label>
                   <div class="col-sm-9">
                    <input type="text" class="form-control " id="day" placeholder="{{date('d M Y')}}" value="{{date('d M')}}" disabled />
                    <input type="hidden" name="day" value="{{date('d')}}">
                   </div>
                   </div>
                   <br />
                 <div class="form-group">
                 <label for="inputTime" class="col-sm-3 control-label">Time</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" id="time" name="time" placeholder="Hours" value="">
                    </div>
                </div>
                <div class="form-group">
                 <label for="inputCom" class="col-sm-3 control-label">Comments</label>
                    <div class="col-sm-9">
                    <input type="text" class="form-control" id="com" name="com" placeholder="Your Comments" value="">
                    </div>
                </div>
                <br />
            <br />
            <div class="modal-footer">

    {{ Form::submit('Add', array('class' => 'btn btn-primary pull-right')) }}

    {{ Form::close() }}
            
            </div>
        </div>
      </div>
  </div></div>

<input type="hidden" class="ajax" />

@endsection