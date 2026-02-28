 <div class="memory-card" data-memory-id="1">
     <div class="card-inner">
         <div class="card-front">
             <div class="card-content">
                 <div class="memory-date">{{ $h1 }}</div>
                 <h3>{{ $h2 }}</h3>
                 <div class="memory-image">
                     <i class="fa {{ $icon ?? 'dot' }} fa-3x"></i>
                     <div class="glitch-effect"></div>
                 </div>
                 <p class="memory-preview">
                     {{ $text }}
                 </p>
                 <div class="card-glow"></div>
             </div>
         </div>



         <div class="card-back">
             <div class="card-content">
                 <h3>Debugging the Matrix123</h3>
                 <p>
                     {!! $back !!}
                 </p>
                 <div class="memory-coordinates">
                     <span><i class="fa-solid fa-location-{{ $icon ?? 'dot' }}"></i>
                         localhost:3000</span>
                     <span class="time-stamp"><i class="fa-regular fa-clock"></i>
                         14:30:15</span>
                 </div>
             </div>
         </div>
     </div>
 </div>
