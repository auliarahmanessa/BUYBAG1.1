@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Category</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route ('categories.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="{{ isset($category) ? route('categories.update', $category->id) : '#' }}" method="POST" id="categoryForm" name="categoryForm">
        <div class="card">
            <div class="card-body">								
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{ old('name', $category->name ?? '') }}">

                            <p></p>	
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" value="{{ old('slug', $category->slug ?? '') }}">

                            <p></p>
                        </div>
                    </div>	
                    <div class="col-md-6">
                        <div class="mb-3">
                            {{-- <input type="text" id="image_id" name="image_id" value="">
                        <label for="image"> Image</label> --}}
                        <div id="image" class="dropzone dz-clickable">
                        <div class="dz-message needsclick">
                        <br>Drop files here or click to upload.<br><br>
                        </div>
                        </div>
                        </div>
                        <div>
                            @if(!empty($category->image))
                            <div>
                            <img width="250" src="{{ asset('uploads/category/thumb/'.
                            $category->image) }}" alt="">
                            </div>
                            @endif
                        </div>
                        </div>                        
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option {{ isset($category) && $category->status == 1 ? 'selected' : '' }}>
                                Active</option>
                                <option {{ isset($category) && $category->status == 0 ? 'selected' : '' }}>
                                Block</option>    
                            </select>	
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status">Show on Home</label>
                            <select name="showHome" id="showHome" class="form-control">
                                <option {{ isset($category) && $category->showHome == 'Yes' ? 'selected' : '' }}
                                value="Yes">Yes</option>
                                <option {{ isset($category) && $category->showHome == 'No' ? 'selected' : '' }}
                                value="No">No</option>    
                            </select>	
                        </div>
                    </div>								
                </div>
            </div>							
        </div>
        <div class="pb-5 pt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route ('categories.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
        </div>
        </form>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>
$("#categoryForm").submit(function(event) {
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ isset($category) ? route("categories.update", $category->id) : "#" }}',
        type: 'put',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
    $("button[type=submit]").prop('disabled', false);
            if(response["status"] == true){

                window.location.href="{{ route ('categories.index')}}";

                $("#name").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

                $("#slug").removeClass('is-invalid')
                .siblings('p')
                .removeClass('invalid-feedback').html("");

            } else{
                var errors = response['errors'];
                if (errors['name']) {
                    $("#name").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['name']);
                }else {
                    $("#name").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }

                if (errors['slug']) {
                    $("#slug").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback').html(errors['slug']);
                }else {
                    $("#slug").removeClass('is-invalid')
                    .siblings('p')
                    .removeClass('invalid-feedback').html("");
                }


            }
            

        }, error: function(jqXHR, exception){
        console.log("Something went wrong");
        }
    })
});

$(document).ready(function(){
        $('#title').on('input', function() {
            var element = $(this);  // Ambil nilai input title
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route("getslug") }}',  // Ini akan diproses oleh Laravel Blade untuk menggantikan URL yang sesuai
                type: 'get',
                data: { title: element.val() },
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if(response.status == true) {
                        // Pastikan ID slug sudah benar
                        $("#slug").val(response.slug);  // Menampilkan slug hasil input title
                    }
                }
            });
        });
    });

    Dropzone.autoDiscover = false;

const dropzone = $("#image").dropzone ({
    init: function() {
        this.on('addedfile', function(file) {
            if (this.files.length > 1) {
                this.removeFile(this.files[0]); // Hapus file sebelumnya jika ada lebih dari satu file
            }
        });
    },
    url: "{{ route('temp-images.create') }}",
    maxFiles: 1,
    paramName: 'image',
    addRemoveLinks: true,
    acceptedFiles: "image/jpeg,image/png,image/gif",
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    success: function(file, response) {
        $("#image_id").val(response.image_id);
        //console.log(response)
    }
});



</script>
    
@endsection