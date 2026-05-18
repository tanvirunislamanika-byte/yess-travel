<x-app-layout>
<!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1>Admin Login</h1>
    </div>
</div>
<!-- Page title end -->


<!-- Page content start -->
<div class="innerpagewrap">
    <div class="container register-form">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-10">


        <div class="account-main">
            <div class="account-title">
                <h3>Sign in to Your Account</h3>
            </div>
            <x-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}" class="needs-validation">
            @csrf

                <div class="form-outline mb-4 mt-4">
                     <div class="account-form-label"><label>Your Email</label></div>
                        <input type="email" name="email" id="" class="form-control" placeholder="Enter Your Email" required>
                        <div class="invalid-feedback">
                           This Filed is required
                         </div>
                     </div>

               
                <div class="form-outline mb-3">
                    <div class="account-form-label"><label>Your Password</label> </div>
                    <input type="password" name="password" id="" class="form-control" placeholder="Password" required>
                    <div class="invalid-feedback">
                     This Filed is required
                   </div>
                </div>

                <!-- Remember me -->
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" value="" id="form2Example31" checked="">
                    <label class="form-check-label" for=""> Remember me </label>
                </div>

                <!-- Submit button -->
                <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-block w-100">Login</button>
                </div>

                <div class="demo-credentials mt-4 p-3 border rounded">
                    <strong>Demo Login</strong>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span id="demoEmail">yesstravel@gmail.com</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyText('demoEmail')">
                            Copy
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span id="demoPassword">12345678</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyText('demoPassword')">
                            Copy
                        </button>
                    </div>
                </div>
                <script>
                    function copyText(id) {
                        let text = document.getElementById(id).innerText;

                        navigator.clipboard.writeText(text).then(function() {
                            alert("Copied: " + text);
                        });
                    }
                </script>

                <div class="account-bottom-text mt-5">
                    <p>Don't have an account?  <a href="{{url('/register')}}">Sign Up for free</a></p>
                </div>

            </form>
        </div>

        </div>
        </div>
    </div>
    </div>
<!--  end -->



</x-app-layout>
