{{ Form::open(array('route' => 'route.name', 'method' => 'POST')) }}
	<ul>
		<li>
			{{ Form::label('id', 'Id:') }}
			{{ Form::text('id') }}
		</li>
		<li>
			{{ Form::label('comment', 'Comment:') }}
			{{ Form::textarea('comment') }}
		</li>
		<li>
			{{ Form::label('youtubeUrl', 'YoutubeUrl:') }}
			{{ Form::textarea('youtubeUrl') }}
		</li>
		<li>
			{{ Form::label('displayName', 'DisplayName:') }}
			{{ Form::text('displayName') }}
		</li>
		<li>
			{{ Form::label('profilePictureUrl', 'ProfilePictureUrl:') }}
			{{ Form::text('profilePictureUrl') }}
		</li>
		<li>
			{{ Form::label('approvalStatus', 'ApprovalStatus:') }}
			{{ Form::text('approvalStatus') }}
		</li>
		<li>
			{{ Form::label('rep', 'Rep:') }}
			{{ Form::text('rep') }}
		</li>
		<li>
			{{ Form::submit() }}
		</li>
	</ul>
{{ Form::close() }}