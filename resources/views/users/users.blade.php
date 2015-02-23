{{ Form::open(array('route' => 'route.name', 'method' => 'POST')) }}
	<ul>
		<li>
			{{ Form::label('username', 'Username:') }}
			{{ Form::text('username') }}
		</li>
		<li>
			{{ Form::label('password', 'Password:') }}
			{{ Form::text('password') }}
		</li>
		<li>
			{{ Form::label('user_level', 'User_level:') }}
			{{ Form::text('user_level') }}
		</li>
		<li>
			{{ Form::label('email', 'Email:') }}
			{{ Form::text('email') }}
		</li>
		<li>
			{{ Form::label('account_creation_ip', 'Account_creation_ip:') }}
			{{ Form::text('account_creation_ip') }}
		</li>
		<li>
			{{ Form::label('rememberToken', 'RememberToken:') }}
			{{ Form::text('rememberToken') }}
		</li>
		<li>
			{{ Form::submit() }}
		</li>
	</ul>
{{ Form::close() }}