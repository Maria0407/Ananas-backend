<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Wall</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <? session_start(); 
           $_SESSION['ajax_lastid']=0; ?>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                @if (App\Post::all()->count()==0)
                    <div class="title m-b-md">
                        Post anything here! ;)
                    </div>
                @else
                    <ul>
                        @foreach ($posts as $post)
                            <li>
                                <div>
                                    {{$post->author}}
                                    <span>{{$post->content}}</span>
                                    <span>{{$post->created_at}}</span>
                                    @auth
                                        <?$int=$post->created_at->diff(now());
                                          $postid=$post->id;?>
                                        @if (($post->user_id==auth()->id())&&($int->days<1))
                                            <a href="/{{$postid}}_delete">Delete</a>
                                        @endif
                                    @endauth
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <?$lastpostid=$posts->last()->id; $_SESSION['last_postid']=$lastpostid;?>
                    @if (App\Post::first()->id<$lastpostid)
                        <button id="{{$lastpostid}}" class="show_more" title="Load more posts">Show more...</button>
                    @endif
                    
                @endif

            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $(document).on('click','.show_more',function(){
                    var ID = $(this).attr('id');
                    $('.show_more').hide();
                    
                    $.ajax({
                        type: "get",
                        url:'ajax_more',
                        accepts: "application/x-html",
                        dataType: "html",
                        data:'id='+ID,
                        crossDomain: true,
                        headers: {
                            'Access-Control-Allow-Origin': '*',
                            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE',
                            'Access-Control-Allow-Headers': 'Content-Type, Accept',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }, 
                        success:function(html){
                            $('ul').append(html);

                        },
                        error: function() {
                            alert("error");
                        }
                    });
                });
            });
        </script>
    </body>
</html>
