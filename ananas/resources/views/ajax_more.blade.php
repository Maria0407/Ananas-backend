<?session_start();?>
    <? 
		if(isset($_SESSION['last_postid'])) {
		    if ($_SESSION['ajax_lastid']==0)
		    	{$req_id=$_SESSION['last_postid'];}
		    else {$req_id=$_SESSION['ajax_lastid'];}
		    
		    $totalRowCount = DB::table('posts')
		    					->where('id','<',$req_id)
		    					->orderBy('id','desc')
		    					->count();
		    
		    $showLimit = 20;
		    
		    $posts = DB::table('posts')
		    			->where('id','<',$req_id)
		    			->orderBy('id','desc')
		    			->limit($showLimit)
		    			->get();

		    
		    if($posts->count() > 0){ ?>
		    	
			    	 @foreach ($posts as $post)
			    		<li>
	                        <div>
	                            {{$post->author}}
	                            <span>{{$post->content}}</span>
	                            <span>{{$post->created_at}}</span>
	                            @auth
                                    <?  $format = 'Y-m-d H:i:s';
										$date = DateTime::createFromFormat($format, $post->created_at);
										$int=$date->diff(now());
                                        $postid=$post->id;?>
                                    @if (($post->user_id==auth()->id())&&($int->days<1))
                                        <a href="/{{$postid}}_delete">Delete</a>
                                    @endif
                                @endauth
	                        </div>
	                    </li>
			    	@endforeach
		    	
		    	<?$lastpostid=$posts->last()->id;
		    		$_SESSION['ajax_lastid']=$lastpostid;?>
                @if (App\Post::first()->id<$lastpostid)
                    <button id="{{$_SESSION['ajax_lastid']}}" class="show_more" title="Load more posts">Show more...</button>
                @endif   
		<?
		    }
		}
		else echo "Session variable not set!";
	?>

