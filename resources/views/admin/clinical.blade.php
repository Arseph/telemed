@extends('layouts.app')

@section('content')
<style>
    label {
        padding: 0px;
    }
    .form-group {
        margin-bottom: 10px;
    }
    .disAble {
        pointer-events:none;
    }
</style>
<div class="container">
    <div class="box box-success">
        <div class="box-header with-border">
        <form id="facility_form" method="POST">
            <div class="pull-right">
                <button title="save" type="submit" class="btnSave btn btn-success hide"><i class="far fa-save"></i></button>
                <h4 id="btnEdit" title="Edit Facility" onclick="enableView()"><a href="#"><i class="far fa-edit"></i></a></h4>
            </div>
            <h1 class="title-info">Clinical History and Physical Exam</h1>
            <label class="text-primary">Patient: {{ $patient->lname }}, {{ $patient->fname }} {{ $patient->mname }}</label>
        </div>
        <div id="formEdit" class="box-body disAble">
                {{ csrf_field() }}
        </form>
        </div>
    </div>
</div>
@endsection
@section('js')
    @include('admin.scripts.patient')
@endsection

