<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel 8 Ajax CRUD Application</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div style="padding: 30px"></div>
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-header">All Teacher</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Title</th>
                                <th scope="col">Institute</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">

                <div class="card">
                    <div class="card-header">
                        <span id="addT">Add New Teacher</span>
                        <span id="updateT">Update Teacher</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" class="form-control" placeholder="Enter Name">
                            <span class="text-danger" id="nameError"></span>
                        </div>
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" id="title" class="form-control" placeholder="Enter Title">
                            <span class="text-danger" id="titleError"></span>
                        </div>
                        <div class="form-group">
                            <label for="institute">Institute</label>
                            <input type="text" id="institute" class="form-control" placeholder="Enter Institute">
                            <span class="text-danger" id="instituteError"></span>
                        </div>
                        <input type="hidden" id="id">
                        <button type="submit" id="addButton" onclick="addData()" class="btn btn-info">Add</button>
                        <button type="submit" id="updateButton" onclick="updateData()" class="btn btn-info">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $("#addT").show();
        $("#updateT").hide();
        $("#addButton").show();
        $("#updateButton").hide();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Get All Data -------------------------------------------
        function allData(){
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/teacher/all",
                success: function(response){
                    var data = ""
                    $.each(response, function(key, value){
                        // console.log(value.name);
                        data = data + "<tr>"
                        data = data + "<td>"+ value.id +"</td>"
                        data = data + "<td>"+ value.name +"</td>"
                        data = data + "<td>"+ value.title +"</td>"
                        data = data + "<td>"+ value.institute +"</td>"
                        data = data + "<td>"
                        data = data + "<button class='btn btn-sm btn-primary mr-2' onclick='editData("+value.id+")'>Edit</button>"
                        data = data + "<button class='btn btn-sm btn-danger' onclick='deleteData("+value.id+")'>Delete</button>"
                        data = data + "</td>"
                        data = data + "</tr>"
                    });
                    $("tbody").html(data);
                }
            })
        }
        allData();
        // Get All Data End -------------------------------------------

        // // Clear Data
        function clearData(){
            $("#name").val('');
            $("#title").val('');
            $("#institute").val('');
            $("#nameError").text('');
            $("#titleError").text('');
            $("#instituteError").text('');
        }
        // // Add Data -------------------------------------------------------
        function addData(){
            var name = $("#name").val();
            var title = $("#title").val();
            var institute = $("#institute").val();

            // console.log(name, title, institute);
            $.ajax({
                type: "POST",
                dataType: "json",
                data: {name:name, title:title, institute:institute},
                url: "/teacher/store",
                success:function(data){
                    clearData();
                    allData();
                    // console.log('data successfully added');
                    //  Start Alert
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data Added Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    //  End Alert
                },
                error: function(error){
                    $("#nameError").text(error.responseJSON.errors.name),
                    $("#titleError").text(error.responseJSON.errors.title),
                    $("#instituteError").text(error.responseJSON.errors.institute)
                }
            })
        }
        // // Add Data End -------------------------------------------------------

        // // Edit Data Start -------------------------------------------------------
        function editData(id){
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "/teacher/edit/" + id,
                success: function(data){
                    $("#addT").hide();
                    $("#updateT").show();
                    $("#addButton").hide();
                    $("#updateButton").show();

                    $("#id").val(data.id);
                    $("#name").val(data.name);
                    $("#title").val(data.title);
                    $("#institute").val(data.institute);
                    // console.log(data);
                }
            })
        }
        // // Edit Data End -------------------------------------------------------

        // // Update Data Start -------------------------------------------------------
        function updateData(){
            var id = $("#id").val();
            var name = $("#name").val();
            var title = $("#title").val();
            var institute = $("#institute").val();

            $.ajax({
                type: "POST",
                dataType: "json",
                data: {name:name, title:title, institute:institute},
                url: "/teacher/update/" + id,
                success: function(data){
                    $("#addT").show();
                    $("#updateT").hide();
                    $("#addButton").show();
                    $("#updateButton").hide();
                    clearData();
                    allData();
                    // console.log('data Updated');

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Data Updated Successfully',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    //  End Alert
                },
                error: function(error){
                    $("#nameError").text(error.responseJSON.errors.name),
                    $("#titleError").text(error.responseJSON.errors.title),
                    $("#instituteError").text(error.responseJSON.errors.institute)
                }
            })
        }
        // // Update Data End -------------------------------------------------------
        // // Delete Data Start -------------------------------------------------------
        function deleteData(id){
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this data!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: "/teacher/destroy/"+id,
                        success: function(data){
                            $("#addT").show();
                            $("#updateT").hide();
                            $("#addButton").show();
                            $("#updateButton").hide();
                            clearData();
                            allData();

                            Swal.fire(
                                'Deleted!',
                                'Your Data has been deleted.',
                                'success'
                            )
                        }
                    });

                }
            })
        }
        // // Delete Data End -------------------------------------------------------

    </script>
</body>
</html>
