


@php
function time_since($start)
{
    $end = time();
    $diff = $end - $start;
    $days = floor($diff / 86400); //calculate the days
    $diff = $diff - ($days * 86400); // subtract the days
    $hours = floor($diff / 3600); // calculate the hours
    $diff = $diff - ($hours * 3600); // subtract the hours
    $mins = floor($diff / 60); // calculate the minutes
    $diff = $diff - ($mins * 60); // subtract the mins
    $secs = $diff; // what's left is the seconds;
    if ($secs != 0) {
        $secs .= " seconds";
        if ($secs == "1 seconds") {
            $secs = "1 second";
        }

    } else {
        $secs = '';
    }

    if ($mins != 0) {
        $mins .= " mins ";
        if ($mins == "1 mins ") {
            $mins = "1 min ";
        }

        $secs = '';
    } else {
        $mins = '';
    }

    if ($hours != 0) {
        $hours .= " hours ";
        if ($hours == "1 hours ") {
            $hours = "1 hour ";
        }

        $secs = '';
    } else {
        $hours = '';
    }

    if ($days != 0) {
        $days .= " days ";
        if ($days == "1 days ") {
            $days = "1 day ";
        }

        $mins = '';
        $secs = '';
        if ($days == "-1 days ") {
            $days = $hours = $mins = '';
            $secs = "less than 10 seconds";
        }
    } else {
        $days = '';
    }

    return "$days $hours $mins $secs ago";
}
@endphp
 
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<style>
    /*
    Image credits:
    uifaces.com (http://uifaces.com/authorized)
*/

#login { display: none; }

img.tooltipHere.main-logo {
    width: 78px !important;
}
.reviews {
    color: #555;    
    font-weight: bold;
    margin: 10px auto 20px;
}
.notes {
    color: #999;
    font-size: 12px;
}
.media .media-object { max-width: 120px; }
.media-body { position: relative; }
.media-date { 
    position: absolute; 
    right: 25px;
    top: 25px;
}
.media-date li { padding: 0; }
.media-date li:first-child:before { content: ''; }
.media-date li:before { 
    content: '.'; 
    margin-left: -2px; 
    margin-right: 2px;
}
.media-comment { margin-bottom: 20px; }
.media-replied { margin: 0 0 20px 50px; }
.media-replied .media-heading { padding-left: 6px; }

.btn-circle {
    font-weight: bold;
    font-size: 12px;
    padding: 6px 15px;
    border-radius: 20px;
}
.btn-circle span { padding-right: 6px; }
.embed-responsive { margin-bottom: 20px; }
.tab-content {
    padding: 50px 15px;
    border: 1px solid #ddd;
    border-top: 0;
    border-bottom-right-radius: 4px;
    border-bottom-left-radius: 4px;
}
.custom-input-file {
    overflow: hidden;
    position: relative;
    width: 120px;
    height: 120px;
    background: #eee url('https://s3.amazonaws.com/uifaces/faces/twitter/walterstephanie/128.jpg');    
    background-size: 120px;
    border-radius: 120px;
}
input[type="file"]{
    z-index: 999;
    line-height: 0;
    font-size: 0;
    position: absolute;
    opacity: 0;
    filter: alpha(opacity = 0);-ms-filter: "alpha(opacity=0)";
    margin: 0;
    padding:0;
    left:0;
}
.uploadPhoto {
    position: absolute;
    top: 25%;
    left: 25%;
    display: none;
    width: 50%;
    height: 50%;
    color: #fff;    
    text-align: center;
    line-height: 60px;
    text-transform: uppercase;    
    background-color: rgba(0,0,0,.3);
    border-radius: 50px;
    cursor: pointer;
}
.custom-input-file:hover .uploadPhoto { display: block; }
</style>
<div class="container" style="
margin-top: 5%;
">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1" id="logout">
        
            <div class="comment-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#comments-logout" role="tab" data-toggle="tab" style="
                        display: none;
                    ">
                            <h4 class="reviews text-capitalize">Comments</h4>
                        </a></li>
                    <li style="
                    display: none;
                "><a href="#add-comment" role="tab" data-toggle="tab">
                            <h4 class="reviews text-capitalize">Add comment</h4>
                        </a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="comments-logout">
                        <ul class="media-list">

                            @foreach(DB::table('comment')->orderBy('id','desc')->where('Topic',$post->id)->get() as $item_comment)
                            <li class="media">
                                <a class="pull-left" href="#">
                                    <img class="media-object img-circle"
                                        src="https://image.freepik.com/free-vector/businessman-character-avatar-isolated_24877-60111.jpg"
                                        alt="profile" style="width: 77%; display: none;">
                                </a>
                                <div class="media-body">
                                    <div class="well well-lg">
                                        <h4 class="media-heading text-uppercase reviews">
                                            @if ($post->contact_name===$item_comment->User)
                                                A-
                                            @else
                                             B   -
                                            @endif
                                            
                                            {{ $post->contact_name}} </h4>
                                        <label style="
                                        background: white;
                                        font-size: 110%;
                                        padding: 1%;
                                        direction: ltr;
                                        box-shadow: 0px 0px 1px 0px black;
                                    ">{{ time_since($item_comment->Time)}}</label>
                                    
                                        <p class="media-comment" style="
                                        font-size: 17px;
                                        font-weight: bold;
                                    ">
                                            {{ $item_comment->comment}}
                                        </p>
                                    
                                    </div>
                                </div>
                               
                            </li>
                            @endforeach
                            @if (Auth::check())
                            <div class="tab-pane" id="add-comment">
                                <form action="{{ route('Comments.post') }}" method="post" class="form-horizontal" id="commentForm" role="form">
                                    <input type="hidden" name="Topic" id="" value="{{ $post->id }}">
                                    <input type="hidden" name="User" id="" value="{{ auth()->user()->name }}">
        
                                    <div class="form-group">
                                        <label for="email" class="col-sm-2 control-label">Comment</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="comment" id="addComment"
                                                rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button class="btn btn-success btn-circle text-uppercase" type="submit"
                                                id="submitComment"> ارسال
                                                </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @else
                            <h2>برجاء تسجيل الدخول لكتابة التعليق</h2>
                            @endif
                        </ul>
                    </div>
            
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1" id="login">
      
            <div class="comment-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#comments-login" role="tab" data-toggle="tab">
                            <h4 class="reviews text-capitalize">Comments</h4>
                        </a></li>
                    <li><a href="#add-comment-disabled" role="tab" data-toggle="tab">
                            <h4 class="reviews text-capitalize">Add comment</h4>
                        </a></li>
                    <li><a href="#new-account" role="tab" data-toggle="tab">
                            <h4 class="reviews text-capitalize">Create an account</h4>
                        </a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="comments-login">
                        <ul class="media-list">
            
                     
                        </ul>
                    </div>
                    <div class="tab-pane" id="add-comment-disabled">
                        <div class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert">
                                <span aria-hidden="true">×</span><span class="sr-only">Close</span>
                            </button>
                            <strong>Hey!</strong> If you already have an account <a href="#"
                                class="alert-link">Login</a> now to make the comments you want. If you do not have an
                            account yet you're welcome to <a href="#" class="alert-link"> create an account.</a>
                        </div>
                        <form action="#" method="post" class="form-horizontal" id="commentForm" role="form">
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Comment</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" name="addComment" id="addComment" rows="5"
                                        disabled></textarea>
                                </div>
                            </div>
                     
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button class="btn btn-success btn-circle text-uppercase disabled" type="submit"
                                        id="submitComment">أرسال
                                        </button>
                                </div>
                            </div>
                        </form>
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</div>
