@extends('admin.app')

@section('content')
<div class="container-fluid">
    <div class="card card-rounded ">
        <div class="card-header">
            <div class="row">
                <div class="col-md-4">
                    <h3>Subject Detail</h3>
                </div>
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-2">
                    <a href="{{ route('subject.create') }}" class="btn btn-primary me-2">Add Subject</a> 
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('admin/flash-message')
            <table class="table table-striped" id="DataTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Action</th>   
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    oTable = $("#DataTable").DataTable({
        processing : true,
        serverSide : true,
        ajax : {
            method : "post",
            url : "{{ route('ajax_fetchsubject') }}",
            data : {"_token":"{{ csrf_token() }}"},
        }, 
    });
    
    $(document).on('click', '.delete', function(event){
        var id = $(this).data('value');  
        swal({
            title: "Are you sure?",
            text: "You are not be able to recover this data!",
            type: "warning",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            cancelButtonText: "No, cancel please!",
            cancelButtonClass: "btn-success",
            showCancelButton: true,
            closeOnConfirm: false,
            closeOnCancel: false,
            reverseButtons: true
        },function(isConfirm) {
            if(isConfirm){
                document.getElementById('delete_form'+id).submit();
            }else {
                swal("Cancelled", "Data is safe :)", "error");
                location.reload();
            }
        });    
    });
    
    $(document).on('change','#change_status',function(){
        var status = $(this).val();
        var ids = [];
        var searchIDs = $("#DataTable input:checkbox:checked").map(function(){
            ids.push($(this).val());
        }).get();
        if(ids.length != 0)
        {
            swal({
                title: "Are you sure?",
                text: "Sure To change User Status",
                type: "success",
                confirmButtonClass: "btn-success",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                cancelButtonClass: "btn-warning",
                showCancelButton: true,
                closeOnConfirm: false,
                closeOnCancel: false,
            },function(isConfirm) {
                if(isConfirm){
                    $.ajax({
                        method : "POST" ,
                        url : '{{ route('change_multiple_status') }}',
                        data : {"_token":"{{ csrf_token() }}" , 'id' : ids ,'status':status},
                        success : function(data)
                        {
                            swal('Change Status',data[1],"success");
                            location.reload();
                        }
                    });
                }else {
                    swal("Cancelled", "Data is safe :)", "error");
                    location.reload();
                }
            });
        }
        else{
            swal("Sorry! You have not selected any row", "Data is safe :)", "error");
        }
    });
    
</script>
@endsection