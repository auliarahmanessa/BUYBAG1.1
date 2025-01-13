<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <section class="section-5 pt-3 pb-3 bg-white">
        <div class="container">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Register</li>
            </ol>
        </div>
    </section>

    <section class="section-10">
        <div class="container">
            <div class="login-form bg-light p-4 shadow rounded">
                <form id="registrationForm" method="post">
                    <h4 class="mb-4 text-center">Register Now</h4>
                    
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                        <p class="text-danger small"></p>
                    </div>

                    <div class="form-group">
                        <input type="email" class="form-control" placeholder="Email" id="email" name="email">
                        <p class="text-danger small"></p>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Phone" id="phone" name="phone">
                        <p class="text-danger small"></p>
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                        <p class="text-danger small"></p>
                    </div>

                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                        <p class="text-danger small"></p>
                    </div>

                    <div class="form-group small text-right">
                        <a href="#" class="forgot-link">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn btn-dark btn-block">Register</button>
                </form>
                
                <div class="text-center small mt-3">
                    Already have an account? <a href="login.php">Login Now</a>
                </div>
            </div>
        </div>
    </section>

    <script>
        $("#registrationForm").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: '{{ route("account.processRegister") }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    var errors = response.errors;

                    if (response.status === false) {
                        ["name", "email", "password"].forEach(function(field) {
                            if (errors[field]) {
                                $("#" + field).siblings("p").addClass('invalid-feedback').html(errors[field]);
                                $("#" + field).addClass('is-invalid');
                            } else {
                                $("#" + field).siblings("p").removeClass('invalid-feedback').html('');
                                $("#" + field).removeClass('is-invalid');
                            }
                        });
                    }
                },
                error: function() {
                    console.log("Something went wrong");
                }
            });
        });
    </script>
</body>
</html>
