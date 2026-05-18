<?php 

namespace App\Http\Controllers;

use App\Models\ModulesData;
use App\Models\Review;
use App\Models\Hotels;
use Illuminate\Http\Request;
use App\Mail\ReviewPostedMail;
use Illuminate\Support\Facades\Mail;

class ReviewController extends Controller
{
    // Ensure that the user is logged in before allowing them to submit a review
    public function __construct()
    {
        $this->middleware('auth')->only('store');
    }

    public function reviews($value='')
    {
        $reviews = Review::where('user_id',auth()->user()->id)->get();
        return view('my_reviews', compact('reviews'));
    }

    // Show reviews for a specific hotel
    public function index($id)
    {
        $hotel = ModulesData::where('id',$id)->first();
        // Fetch reviews for the hotel, along with the associated user
        $reviews = $hotel->reviews()->with('user')->latest()->paginate(10);
        
        // Calculate average rating and total reviews
        $averageRating = $hotel->reviews()->avg('rating');
        $totalReviews = $hotel->reviews()->count();

        // Render the reviews list as HTML (you'll need a Blade view 'reviews.list')
        $review_html = view('reviews.list', compact('reviews','hotel'))->render();

        // Return the response with review HTML, average rating, and total review count
        return response()->json([
            'status' => 'success',
            'review_html' => $review_html,
            'average_rating' => number_format($averageRating, 1),  // Round to 1 decimal place
            'total_reviews' => $totalReviews
        ]);
    }

    // Store a new review for a specific hotel
    public function store(Request $request)
{
    // Ensure the user is authenticated
    if (!auth()->check()) {
        return response()->json(['error' => 'You must be logged in to submit a review.'], 403);
    }

    // Validate the review form input
    $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'reason' => 'required|string',
        'review_text' => 'required|string',
        'hotel_id' => 'required|exists:modules_data,id',
    ]);

    // Check if the user has already submitted a review for this hotel
    $review = Review::where('hotel_id', $validated['hotel_id'])
                    ->where('user_id', auth()->id())
                    ->first();

    if ($review) {
        // Update the existing review
        $review->rating = $validated['rating'];
        $review->reason = $validated['reason'];
        $review->review = $validated['review_text'];
        $review->updated_at = now();
        $review->save();
    } else {
        // Create a new review
        $review = new Review();
        $review->rating = $validated['rating'];
        $review->reason = $validated['reason'];
        $review->review = $validated['review_text'];
        $review->hotel_id = $validated['hotel_id'];
        $review->user_id = auth()->id();
        $review->save();
    }

    // Update hotel status if the review was just created
    $hotel = Hotels::where('hotel_id', $validated['hotel_id'])
                    ->where('user_id',auth()->user()->id)
                    ->where('booking_status', 'Confirmed')
                    ->first();

    if ($hotel) {
        $hotel->rating = 'completed';
        $hotel->update();
    }

    // Calculate the updated average rating and total reviews
    $averageRating = $review->hotel->reviews()->avg('rating');
    $totalReviews = $review->hotel->reviews()->count();
    
    // Render the single review as HTML
    $review_html = view('reviews.single', compact('review'))->render();

    $adminEmail = widget(1)->extra_field_2;
    Mail::to($adminEmail)->send(new ReviewPostedMail($review, auth()->user(), $review->hotel));

    // Return the response with success status, new review HTML, average rating, and total review count
    return response()->json([
        'success' => true,
        'review_html' => $review_html,
        'average_rating' => number_format($averageRating, 1),
        'total_reviews' => $totalReviews,
    ]);
}



    public function store_reply(Request $request)
    {

        // Validate the review form input
        $validated = $request->validate([
            'review_id' => 'required',
            'reply' => 'required|string'
        ]);

        // Create and save the review
        $review = Review::where('id',$request->review_id)->first();
        $review->reply = $request->reply;
        $review->save();


        $request->session()->flash('message.added', 'success');
        $request->session()->flash('message.content', 'Reply has been successfully Posted!');

        return redirect()->back();

        
    }
}