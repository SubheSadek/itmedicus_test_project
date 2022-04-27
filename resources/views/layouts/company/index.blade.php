@extends('layouts.app')


@section('content')

    <!-- Content Header (Page header) -->
       <section class="content-header">
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1>Company Datatable</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Company</li>
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
                      <h3 class="card-title">Company info with all features</h3>
                  </div> 
                  <div class="col-sm-6" style="text-align: right;">
                      <button onclick="addModal()" type="button" class="btn btn-primary" data-toggle="modal" data-target="#editCompanyModal">Create New +</button>
                  </div>
                  
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th data-sortable="false">Email</th>
                      <th data-sortable="false">Website</th>
                      <th data-sortable="false">Created at</th>
                      <th data-sortable="false">Company Logo</th>
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

            <div class="modal fade editCompanyModal" id="editCompanyModal">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h4 class="modal-title" style="margin-left:145px;">Edit Company Info</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <!-- form start -->
                      <form id="editCompanyInfo" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body pt-0">
                            <input type="hidden" name="company_id" id="company_id">
                          <div class="form-group">
                            <label>Company name</label>
                            <input type="text" name="name" id="comp_name" class="form-control" placeholder="Enter company name">
                            <span id="company_name" class="invalid-feedback-custom"></span>
                          </div>
            
                          <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" id="comp_email" class="form-control" placeholder="Enter company email">
                            <span id="company_email" class="invalid-feedback-custom"></span>
                          </div>
            
                          <div class="form-group">
                            <label>Website</label>
                            <input type="text" name="website" id="comp_website" class="form-control" placeholder="Enter company email">
                            <span id="company_website" class="invalid-feedback-custom"></span>
                          </div>
                          
                          <div class="form-group">
                            <label>Company Logo</label>
                             <input type="file" name="logo" class="form-control-file" id="filePhoto2">
                             <br>
                              <img class="center" src="" id="previewHolder2" width="100px">
                              <span id="company_logo" class="invalid-feedback-custom"></span>
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
            dom: 'Bfrtip',
            responsive: true,
            autoWidth: true,
            processing: true,
            serverSide: true,
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
            order: [[0, 'desc']],
            ajax: {
                url: '/get-company-data',
                type: 'get',
            },
            columns: [
                {data: 'id'},
                {data: 'name'},
                {data: 'email'},
                {data: 'website'},
                {data: 'created_at', 
                "render": function (value) {
                              if (value === null) return "";
                              return moment(value).format('DD/MM/YYYY');
                          }
                },
                {data: 'logo', "render": function (value) {
                              if (value === "") return "";
                              return '<img height="50%" width="50%" src="'+value+'"/>';
                          }},
                {data: '', name:  ''},
            ],
            
            columnDefs: [
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
                             '<button onclick="edit_action(this, ' + full_row.id + ')" type="button" class="edit_action btn btn-warning btn-xs" data-toggle="modal" data-target="#editCompanyModal" style="margin:3px"><i class="fa fa-edit"></i> Edit</button>' +
                             '</div>';
                     }

                 }
            ],
            
        });
        return table;
     }

     function addModal(){
        $('form').attr("id","addCompanyInfo")
        $('.modal-title').text('Add Company Info')
        $('#addCompanyInfo button[type="submit"]').text('Save')
        $('#editCompanyModal').modal('show')
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
                       url: "{{url('company')}}/"+id + "?_token= {{ csrf_token() }}",
                       method: "DELETE",
                       contentType: false,
                       cache: false,
                       processData: false,
                       success: function(data){
                        table.ajax.reload(null, false);
                           toastr.success('Category Deleted succefully !!');
                       }
                   })
                   
                 }
            })
     }

    function edit_action(this_el, item_id){
        
        $('#company_id').val(item_id);
        var tr_el = this_el.closest('tr');
        var row = table.row(tr_el);
        var row_data = row.data();
        $('#comp_name').val(row_data.name);
        $('#comp_email').val(row_data.email);
        $('#comp_website').val(row_data.website);
        $('#previewHolder2').attr('src', row_data.logo);
    }

    $(document).ready( function () { 
        var table = initDataTable();
        
        $('form').on('submit', function(e){
            e.preventDefault();

            $("#submit_form").prop('disabled', true);
            $("#submit_form").text('Please wait...');
            
            let attr = $(this).attr('id');
            
            let url = attr =='editCompanyInfo' ? 'company-update': 'company';
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: "POST",
                url: "{{ url('') }}" + "/" + url,
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data){
                  $('#editCompanyModal').modal('hide');
                  table.ajax.reload(null, false);
                  toastr.success(attr =='editCompanyInfo' ? 'Company Updated succefully !!' : 'Company created succefully !!');
                  $("#submit_form").prop('disabled', false);
                },
                error: function (error) {
                    var errors = error.responseJSON.errors;
                    // $('#company_name').html('');
                        if(errors.name){
                            $('#company_name').text(errors.name[0]);
                        }; 
                        if(errors.email){
                        $('#company_email').text(errors.email[0]); 
                        }
                        if(errors.website){
                        $('#company_website').text(errors.website[0]); 
                        }
                        if(errors.logo){
                        $('#company_logo').text(errors.logo[0]); 
                        }
                        
                      $("#submit_form").prop('disabled', false);
                      let text = attr =='editCompanyInfo' ? 'Edit' : 'Save';
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

        $('#editCompanyModal').on('hidden.bs.modal', function (e) {
            $(this).find('img').attr('src', '');
            $(this).find('form').trigger('reset');
            $(this).find('form').attr("id","editCompanyInfo")
            $('.modal-title').html('Edit Company Info')
            $('#editCompanyInfo button[type="submit"]').text('Edit')

            $('#editCompanyInfo span').html('');
        })
    });
  </script>
@endsection