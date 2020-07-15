@if($timeline)
<div class="Timeline">
	@foreach ($timeline as $key => $value)
		<svg height="5" width="140">
		  <line x1="0" y1="0" x2="140" y2="0" style="stroke:#004165;stroke-width:5" />
		  Sorry, your browser does not support inline SVG.
		</svg>

	  	<div class="event1">
		    <div class="event1Bubble">
		      <div class="eventTime">
		        <div class="DayDigit">{{ \Carbon\Carbon::parse($key)->format('d') }}</div>
		        <div class="Day">
					{{ \Carbon\Carbon::parse($key)->translatedFormat('l') }}
		          	<div class="MonthYear">{{ \Carbon\Carbon::parse($key)->translatedFormat('F') }}
					  					 {{ \Carbon\Carbon::parse($key)->format('Y')}}
					</div>
		        </div>
		      </div>
		      <div class="eventTitle">{{$value}}<br /><br /></div>
		    </div>
		    {{-- <div class="eventAuthor">by Youri Nelson</div> --}}
		    {{-- <svg height="20" width="20">
		       <circle cx="10" cy="11" r="5" fill="#004165" />
		     </svg> --}}
		    <div class="time">{{ \Carbon\Carbon::parse($key)->format('H:m')}}</div>
	  	</div>
	@endforeach

	<svg height="5" width="140">
	  <line x1="0" y1="0" x2="140" y2="0" style="stroke:#004165;stroke-width:5" />
	  Sorry, your browser does not support inline SVG.
	</svg>

</div>
@endif
