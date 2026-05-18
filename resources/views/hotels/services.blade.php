<x-app-layout>
        <style>
            .navbar-transparent {
    background-color: #2a2a2a !important;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    z-index: 299;
    transition: all 0.25s;
    -webkit-transition: all 0.25s;
}
        </style>
        
        <!-- End #header -->
    
        <div id="content" role="main" class="no-padding">
        
            <div class="bg-lightergray no-overflow pt70 pb50">

                <div class="container ">
                    <div class="row">
                        <div class="col-md-12 wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                            <h1 class="title text-left text-uppercase mb50"><span class="light">Transforming the way that</span> coaches can access and use analysis</h1>
                        </div>
                        <div class="col-md-12">
                            <div class="row">

                                @if(null!==($services))
                                @foreach($services as $service)
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                                        <span class="service-icon first-color"><i class="fa fa-bar-chart"></i></span>
                                        <div class="service-content service wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                                            <h3 class="service-title title service wow fadeInUp greenBG animated" style="visibility: visible; animation-name: fadeInUp;">{{$service->title}}</h3>
                                            <p>
                                                {{$service->extra_field_1}}
                                            </p>
                                            <p>
                                                <a href="{{route('services.detail',$service->slug)}}" alt="{{$service->extra_field_2}}" class="btn btn-success btn-sm active" target="_blank" title="{{$service->extra_field_2}}">{{$service->extra_field_2}}</a>
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                                @endforeach
                                @endif


                                <!-- End .col-sm-6 -->
                                <!-- End .col-sm-6 -->
                            </div><!-- End .row -->
                        </div> 
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="service vertical text-left wow fadeInUp animated" style="visibility: visible; animation-name: fadeInUp;">
                                        <img src="https://s3-eu-west-1.amazonaws.com/web.imedialibrarysports.com/images/sports-performance-analysis/iSportsAnalysis-Performance-Analysis-Borders-GreyBG-2.png" alt="iSportsAnalysis - Performance Analysis" class="image-size">
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                            </div>
                        </div>
                    </div>
                </div><!-- End .container -->

                <hr class="gray mt15 mb65 mt35-sm  wow fadeInUp" style="visibility: hidden; animation-name: none;">   
                
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 wow fadeInUp" style="visibility: hidden; animation-name: none;">
                            <h2 class="title text-left text-uppercase mb50"><span class="light">The</span> Benefits</h2>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <span class="service-icon first-color"><i class="fa fa-bar-chart"></i></span>
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Any Sport, Any Device, <span class="light"> Anywhere</span></h3>

                                            <p>
                                                Access the iSportsAnalysis analysis platform, and on any device – no need for software to be installed on individual laptops/tablets/phones.
                                            </p>
                                            <p>
                                                Invaluable online video analysis technology combines with in-depth data analysis capability to provide the complete analysis solution.
                                            </p>
                                            <p>
                                                Instant sharing of analysis. Your video and analysis are immediately available to (selected) staff &amp; players.
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <span class="service-icon first-color"><i class="fa fa-bar-chart"></i></span>
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Designed by<span class="light"> Coaches and Players</span></h3>

                                            <p>
                                                Each coach and player has their own private login which means that each individual can access the platform at a time and place that suits them.
                                            </p>
                                            <p>
                                                You can import data from other analysis software solutions (e.g. SportsCode), instantly populating your event list in your iSportsAnalysis session and enabling interactive review, data analysis etc.
                                            </p>
                                            <p>
                                                Invaluable drawing, animation and session planning technology to enhance the visualisation of your information and feedback.
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <span class="service-icon first-color"><i class="fa fa-bar-chart"></i></span>
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Analyse Players, <span class="light"> Analyse Coaches</span></h3>

                                            <p>
                                                Potential to add our invaluable Player Engagement solution to enable your players to view and analyse their own performance – and exclusive access to the online CAIS solution to view, analyse and better understand your own coaching behaviour.
                                            </p>
                                            <p>
                                                The clean, user-friendly Dashboard design ensures coaches and players can quickly learn and use the platform.
                                            </p>
                                            <p>
                                                Our iSportsAnalysis packages are tailor-made for the specific requirements of you and your team(s).
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->
                        </div><!-- End .col-md-6 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->

                <hr class="gray mt15 mb65 mt35-sm  wow fadeInUp" style="visibility: hidden; animation-name: none;">   
                
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 wow fadeInUp" style="visibility: hidden; animation-name: none;">
                            <h2 class="title text-left text-uppercase mb50"><span class="light">The </span>Features</h2>
                        </div>

                        <div class="col-md-12">
                            
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <span class="service-icon first-color"><i class="fa fa-bar-chart"></i></span>
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Upload any<span class="light"> video format</span></h3>

                                            <p>
                                               Videos are seamlessly uploaded and automatically transcoded to work in iSportsAnalysis.
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <span class="service-icon first-color"><i class="fa fa-bar-chart"></i></span>
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Create your own<span class="light"> analysis templates</span></h3>

                                            <p>
                                               The easy-to-use Template facility enables you to create your own analysis templates or choose one of the standard templates provided.
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <span class="service-icon first-color"><i class="fa fa-bar-chart"></i></span>
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Simple, fast<span class="light"> data entry</span></h3>

                                            <p>
                                               Any important individual, unit or team events can be added quickly and easily in real-time or post-game using the Template facility.
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Interactive<span class="light"> review facility</span></h3>

                                            <p>
                                                The unique interactive review facility allows you to instantly access and view any of the important events that you have entered.
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Auto generation of<span class="light"> performance data</span></h3>

                                            <p>
                                               When events are added, data is automatically generated and collated, ready for quick export. If standard Templates are used the data is also immediately displayed onscreen. 
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Instant<span class="light"> sharing</span></h3>

                                            <p>
                                               All analysis carried out is instantly shared and available to review interactively with those given permission to access.
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Bookmarks<span class="light"> and highlight movies</span></h3>

                                            <p>
                                                You have the ability to bookmark specific events of interest and/or automatically create Highlight movies.
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;">Onscreen<span class="light"> drawing tools</span></h3>

                                            <p>
                                               A range of telestration and onscreen text tools provide the platform for enhancing the value of your feedback to players.
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                                <div class="col-sm-4">
                                    <div class="service vertical text-left wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                        <div class="service-content service wow fadeInUp" style="visibility: hidden; animation-name: none;">
                                            <h3 class="service-title title text-left service wow fadeInUp greenBG" style="visibility: hidden; animation-name: none;"><span class="light"> </span></h3>

                                            <p>
                                            </p>
                                        </div><!-- End .service-content -->
                                    </div><!-- End .service -->
                                </div><!-- End .col-sm-6 -->
                            </div><!-- End .row -->
                            
                        </div><!-- End .col-md-6 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->  
            </div>
</div>
</x-app-layout>