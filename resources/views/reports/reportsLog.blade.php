{{ Form::open(array('route' => 'route.name', 'method' => 'POST')) }}
	<ul>
		<li>
			{{ Form::label('ip', 'Ip:') }}
			{{ Form::text('ip') }}
		</li>
		<li>
			{{ Form::label('reportingId', 'ReportingId:') }}
			{{ Form::text('reportingId') }}
		</li>
		<li>
			{{ Form::submit() }}
		</li>
	</ul>
{{ Form::close() }}