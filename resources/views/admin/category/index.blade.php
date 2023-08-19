@extends('layouts.header')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Ads
            <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @include('layouts.includes.adminlte.alerts')
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="padding-top: 5px;"></h3>
                        <div class="box-tools">

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="
                    {{route('admin.category.create')}}" class="btn btn-primary btn-sm pull-right">Add
                                Category</a>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="overflow: scroll;padding-left: 10px; padding-right: 10px;">




                        <table id="example" class="display" style="width:100%">
                            <!--<table id="example1" class="table table-bordered table-striped">`-->
                            <thead>
                                <tr>
                                    <th>
                                        Image
                                    </th>
                                    <th>
                                        Title
                                    </th>
                                    <th>
                                        English Title
                                    </th>
                                    <th>
                                        MainCategoryId
                                    </th>
                                    <th>
                                        priority
                                    </th>
                                    <th>
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($category as $ad)
                                {{-- {{ dd($category) }} --}}
                                <!--{{$ad}}-->
                                <tr>
                                    <td>
                                        <img width="100px" height="100px" src="{{asset('/uploads/category/'.$ad->cat_img)}}">
                                    </td>
                                    <td>{{$ad->arabic_name}}</td>
                                    <td>{{$ad->english_title}}</td>
                                    <td>{{$ad->mainCategoryId}}</td>
                                    <td>{{$ad->priority}}</td>
                                    <td>
                                        <div class="tools">
                                            <!--<a href="{{route('admin.ads.edit',$ad->id)}}"><i class="fa fa-edit"></i></a>-->
                                            <a href="{{route('admin.category.show', $ad->id)}}"><i
                                                    class="fa fa-edit"></i></a>
                                            <a onclick="return confirm('Are you sure?')"
                                                href="{{route('admin.category.delete',$ad->id)}}"
                                                deleteformid="form-3680" class="confirmAction">
                                                <i class="fa fa-trash-o"></i>
                                            </a>

                                        </div>
                                    </td>

                                </tr>

                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>
                                        Image
                                    </th>
                                    <th>
                                        Title
                                    </th>
                                    <th>
                                        City
                                    </th>
                                    <th>Action
                                    </th>
                                </tr>
                        </table>

                    </div>
                    <!-- /.box-body -->

                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
@endsection
