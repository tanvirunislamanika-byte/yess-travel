@foreach ($reviews as $review)
 <li>
 <div class="reviewhead">
    <div class="avatar avatar-lg me-2 flex-shrink-0">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())

        <img class="img-fluid" src="{{ $review->user->profile_photo_url }}" />

        @else
        <img class="img-fluid" src="https://ui-avatars.com/api/?name={{$review->user->name[0]}}" />
        

        @endif</div>
    <div class="">
    <h4>{{ $review->user->name }}</h4>
        <div class="userrateinfo">
        <span>{{ $review->created_at->format('F j, Y') }}</span>
        <div class="comprating">
            @for ($i = 1; $i <= 5; $i++)
                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
            @endfor
        </div>
        <span>({{ $review->reason }}!)</span>
    </div>

        
    </div>
    @if(auth()->user() && auth()->user()->id == 1 && $review->reply== '')
    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#replyreview" data-review="{{$review->id}}" data-feedback="{{$review->review}}" class="replybtn"><i class="fas fa-reply"></i> Reply</a>
    @endif                       
 </div>                     
 <p>{{ $review->review }}</p> 
@if($review->reply)
 <div class="replymsg">
                        <div class="avatar avatar-lg me-2 flex-shrink-0">
                           <img src="https://ui-avatars.com/api/?name=S" alt="">
                        </div>

                        <div class="">
                           <h5>{{@$hotel->title}}</h5>
                           <div class="dateposted">{{ $review->updated_at->format('F j, Y') }}</div>
                           <p>{{ $review->reply }}</p>  
                        </div>                      
                     </div>                      
 @endif
</li>  
@endforeach