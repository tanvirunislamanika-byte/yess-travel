<x-app-layout>
   <!-- Page title start -->
<div class="pageheader">            
    <div class="container">
        <h1>Declined</h1>
    </div>
</div>
<!-- Page title end -->


<!-- Page content start -->
<div class="innerpagewrap">
    <div class="container register-form">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-10">


        <div class="account-main">
           
            <form action="" method="post" class="needs-validation" novalidate>

                <fieldset>
                    <div class="form-card">
                       
                        <h2 class="purple-text text-center"><strong>Declined!</strong></h2> <br>
                        <div class="row justify-content-center">
                        </div> <br><br>
                       
                           
                                <h5 class="purple-text text-center">{{$error}}</h5>
                            
                        
                    </div>

                    <div class="text-center mt-4"><a href="{{url('/')}}" class="btn btn-primary">Go Back to Home Page</a></div>

                </fieldset>
               

            </form>
        </div>

        </div>
        </div>
    </div>
    </div>
<!--  end -->


</x-app-layout>
