@extends('layouts.app')


@section('content')

    <!-- Content Header (Page header) -->
       <section class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1>Employee Datatable</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Employee</li>
                </ol>
                </div>
            </div>
            </div><!-- /.container-fluid -->
       </section>
  
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">

              <div class="card">
                <div class="card-header row">
                  <div class="col-sm-6">
                      <h3 class="card-title">Employee info with all features</h3>
                  </div> 
                  <div class="col-sm-6" style="text-align: right;">
                      <button onclick="addModal()" type="button" class="btn btn-primary" data-toggle="modal" data-target="#editEmployeeModal">Create New +</button>
                  </div>
                  
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>Id</th>
                      <th data-sortable="false">Company name</th>
                      <th>Name</th>
                      <th data-sortable="false">Email</th>
                      <th data-sortable="false">Phone</th>
                      <th data-sortable="false">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </section>
      <!-- /.content -->


        <!--------------------------
            | edit modal start|
        -------------------------->

            <div class="modal fade editEmployeeModal" id="editEmployeeModal">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title" style="margin-left:145px;">Edit Employee Info</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <!-- form start -->
                      <form id="editEmployeeInfo" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body pt-0">
                            <input type="hidden" name="employee_id" id="employee_id">

                          <div class="form-group">
                              <label>Company name</label>
                              <select class="form-control text-capitalize" name="company_id" id="emp_company_id">
                                @foreach ($company as $comp)
                                <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                                  {{-- <option value="{{ $category->id }}" 
                                    @if($category->id == $subcat->cat_id) selected='selected' @endif 
                                    >{{ $category->cat_name }}</option> --}}
                                @endforeach
                            </select>
                          </div>

                          <div class="form-group">
                            <label>First name</label>
                            <input type="text" name="first_name" id="emp_fname" class="form-control" placeholder="Enter first name">
                            <span id="employee_fname" class="invalid-feedback-custom"></span>
                          </div>
            
                          <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" id="emp_lname" class="form-control" placeholder="Enter last name">
                            <span id="employee_lname" class="invalid-feedback-custom"></span>
                          </div>
            
                          <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" id="emp_email" class="form-control" placeholder="Enter your email">
                            <span id="employee_email" class="invalid-feedback-custom"></span>
                          </div>
            
                          <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" id="emp_phone" class="form-control" placeholder="Enter your phone">
                            <span id="employee_phone" class="invalid-feedback-custom"></span>
                          </div>
                          

                        </div>
                        <!-- /.card-body -->
                        <div class="modal-footer justify-content-between border-0 pt-0">
                          <button type="button" class="btn btn-danger" data-dismiss="modal"><b>Close</b></button>
                          <button type="submit" id="submit_form" class="btn btn-success"><b>Edit</b></button>
                        </div>
                      </form>
            
                    </div>
                   
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
            
            <!--------------------------
                | edit modal end|
            -------------------------->

 <script src="{{ asset('assets/plugins/jquery/jquery.min.js')}}"></script>
 <script>
     var table;
     function initDataTable(){
            table = $("#example1").DataTable({
            "dom": 'Bfrtip',
            "responsive": true,
            "autoWidth": true,
            "processing": true,
            "order": [[0, 'desc']],
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "serverSide": true,
            ajax: {
                url: '/get-employee-data',
                type: 'get',
            },
            "columns": [
                {data: 'id'},
                {data: 'company_name'},
                {data: 'full_name'},
                {data: 'email'},
                {data: 'phone'},
                {data: '', name:  ''},
            ],
            
            "columnDefs": [
                 {
                     'targets': -1,
                     'defaultContent': '-',
                     'searchable': false,
                     'orderable': false,
                     'width': '20%',
                     'className': 'dt-body-center',
                     'render': function (data, type, full_row, meta){
                         return '<div style="display:block">' +
                             '<button type="button" onclick="delete_action(' + full_row.id +')" class="delete_action btn btn-danger btn-xs" data-toggle="modal" data-target="#modal_delete" style="margin:3px"><i class="fa fa-remove"></i> Delete</button>' +
                             '<button onclick="edit_action(this, ' + full_row.id + ')" type="button" class="edit_action btn btn-warning btn-xs" data-toggle="modal" data-target="#editEmployeeModal" style="margin:3px"><i class="fa fa-edit"></i> Edit</button>' +
                             '</div>';
                     }

                 }
            ],
            
        });
        return table;
     }

     function addModal(){
        $('form').attr("id","addEmployeeInfo")
        $('.modal-title').text('Add Employee Info')
        $('#addEmployeeInfo button[type="submit"]').text('Save')
        $('#editEmployeeModal').modal('show')
     }

    function delete_action(id){

            Swal.fire({
               title: 'Are you sure to delete?',
               text: "You won't be able to revert this!",
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: 'Yes, delete it!'
               }).then((result) => {
                 if (result.value) {
                   $.ajax({
                       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                       url: "{{url('employee')}}/"+id + "?_token= {{ csrf_token() }}",
                       method: "DELETE",
                       contentType: false,
                       cache: false,
                       processData: false,
                       success: function(data){
                            table.ajax.reload(null, false);
                           toastr.success('Company Deleted succefully !!');
                       }
                   })
                   
                 }
            })
    }

     function edit_action(this_el, item_id){
        
        $('#employee_id').val(item_id);
        var tr_el = this_el.closest('tr');
        var row = table.row(tr_el);
        var row_data = row.data();
        $('#emp_fname').val(row_data.first_name);
        $('#emp_lname').val(row_data.last_name);
        $('#emp_email').val(row_data.email);
        $('#emp_phone').val(row_data.phone);
        $('#emp_company_id').val(row_data.company_id);
    }

    $(document).ready( function () { 
        var table = initDataTable();
        
        $('form').on('submit', function(e){
            e.preventDefault();

            $("#submit_form").prop('disabled', true);
            $("#submit_form").text('Please wait...');

            let attr = $(this).attr('id');
            
            let url = attr =='editEmployeeInfo' ? 'employee-update': 'employee';
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: "POST",
                url: "{{ url('') }}" + "/" + url,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){
                  $('#editEmployeeModal').modal('hide');
                  table.ajax.reload(null, false);
                  toastr.success(attr =='editEmployeeInfo' ? 'Employee Updated succefully !!' : 'Employee created succefully !!');
                  $("#submit_form").prop('disabled', false);
                },
                error: function (error) {
                    var errors = error.responseJSON.errors;
                        if(errors.first_name){
                            $('#employee_fname').text(errors.first_name[0]);
                        }; 
                        if(errors.last_name){
                            $('#employee_lname').text(errors.last_name[0]);
                        }; 
                        if(errors.email){
                             $('#employee_email').text(errors.email[0]); 
                        }
                        if(errors.phone){
                            $('#employee_phone').text(errors.phone[0]); 
                        }

                        $("#submit_form").prop('disabled', false);
                        let text = attr =='editEmployeeInfo' ? 'Edit' : 'Save';
                        $("#submit_form").text(text);
                }
            });
        });

                   //uploaded image preview
        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewHolder2').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#filePhoto2").change(function() {
            readURL2(this);
        });

        $('#editEmployeeModal').on('hidden.bs.modal', function (e) {
            $(this).find('img').attr('src', '');
            $(this).find('form').trigger('reset');
            $(this).find('form').attr("id","editEmployeeInfo")
            $('.modal-title').html('Edit Company Info')
            $('#editEmployeeInfo button[type="submit"]').text('Edit')

            $('#editEmployeeInfo span').html('');
        })
    });
  </script>
@endsection