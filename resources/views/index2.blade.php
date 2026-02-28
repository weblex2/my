<x-noppal>
    <div class="cosmos-background">
        <div class="stars-container"></div>
    </div>

    <div class="container-fluid h-100 d-flex flex-column">
        <main class="flex-grow-1 d-flex align-items-center justify-content-center position-relative">
            <div class="carousel-container">
                <div class="carousel" id="memory-carousel">
                    <x-card h1="Me, myself and I" h2="What you need to know" text="Was ich über mich selbst sage"
                        icon="fa-person-circle-check"
                        back="- leidenschaftlicher Programmierer <br>
                              - Fokus auf PHP und Laravel. <br>
                              - Entwicklung von Webanwendungen<br>
                              - neuen Herausforderungen<br>
                              - Fähigkeiten zu erweitern<br>
                              - das Übliche halt...<br>
                              - das ist meinpersönliches Experimentierfeld<br>
                              - verschiedene Technologien und Konzepte <br>
                              " />

                    <x-card h1="Filament Project" h2="Build your own CRM" text="Easy Configuration" icon="fa-branch"
                        back="Laravel Filament ist ein modernes Admin- und Tooling-Framework, das speziell für Laravel entwickelt wurde. Es liefert dir fertige UI-Komponenten, Admin-Panels und Entwickler-Tools, die sich nahtlos in eine Laravel-Anwendung einfügen, ohne dass du alles von Grund auf selbst programmieren musst." />




                    {{--  <div class="memory-card" data-memory-id="2">
                        <div class="card-inner">
                            <div class="card-front">
                                <div class="card-content">
                                    <div class="memory-date">FRAMEWORK: ReactJS</div>
                                    <h3>Debugging the Matrix</h3>
                                    <div class="memory-image">
                                        <i class="fa-solid fa-bug fa-3x"></i>
                                        <div class="glitch-effect"></div>
                                    </div>
                                    <p class="memory-preview">
                                        The error messages multiplied, haunting my console...
                                    </p>
                                    <div class="card-glow"></div>
                                </div>
                            </div>



                            <div class="card-back">
                                <div class="card-content">
                                    <h3>Debugging the Matrix</h3>
                                    <p>
                                        They appeared from the depths of the console, cryptic error
                                        messages glowing red. Debugging a complex ReactJS component
                                        felt like navigating a vast, interconnected matrix. Each fix
                                        unveiled new issues. They say my code is destabilizing the build
                                        with each change. My presence causes ripples they can't control.
                                        I'm becoming a threat... to clean code.
                                    </p>
                                    <div class="memory-coordinates">
                                        <span><i class="fa-solid fa-location-dot"></i>
                                            localhost:3000</span>
                                        <span class="time-stamp"><i class="fa-regular fa-clock"></i>
                                            14:30:15</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="memory-card" data-memory-id="3">
                        <div class="card-inner">
                            <div class="card-front">
                                <div class="card-content">
                                    <div class="memory-date">CONCEPT: Algorithms</div>
                                    <h3>The Algorithm Library</h3>
                                    <div class="memory-image">
                                        <i class="fa-solid fa-book-open fa-3x"></i>
                                        <div class="glitch-effect"></div>
                                    </div>
                                    <p class="memory-preview">
                                        Endless tomes of sorting, searching, and optimization...
                                    </p>
                                    <div class="card-glow"></div>
                                </div>
                            </div>
                            <div class="card-back">
                                <div class="card-content">
                                    <h3>The Algorithm Library</h3>
                                    <p>
                                        Endless shelves containing every possible solution. I found my
                                        own data structures there—pages still being written as I coded.
                                        The Librarian (my senior developer) told me I was never supposed
                                        to reinvent the wheel. My solution was already optimized. Now I'm
                                        writing outside the margins, trying new approaches.
                                    </p>
                                    <div class="memory-coordinates">
                                        <span><i class="fa-solid fa-location-dot"></i> Stack Overflow</span>
                                        <span class="time-stamp"><i class="fa-regular fa-clock"></i> 11:05:40</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="memory-card" data-memory-id="4">
                        <div class="card-inner">
                            <div class="card-front">
                                <div class="card-content">
                                    <div class="memory-date">PARADIGM: Abstraction</div>
                                    <h3>The Abstract Void</h3>
                                    <div class="memory-image">
                                        <i class="fa-solid fa-atom fa-3x"></i>
                                        <div class="glitch-effect"></div>
                                    </div>
                                    <p class="memory-preview">
                                        Nothing concrete exists here, yet I feel the underlying logic...
                                    </p>
                                    <div class="card-glow"></div>
                                </div>
                            </div>
                            <div class="card-back">
                                <div class="card-content">
                                    <h3>The Abstract Void</h3>
                                    <p>
                                        Nothing concrete exists here, yet I feel the underlying logic.
                                        The Abstract Void is the space between concrete implementations,
                                        a quantum foam of design patterns. I stayed too long designing
                                        and began to dissolve into pure theory. Parts of my ideas are
                                        still there, echoing. I'm not whole anymore. Can you feel the
                                        gaps in my documentation?
                                    </p>
                                    <div class="memory-coordinates">
                                        <span><i class="fa-solid fa-location-dot"></i> design patterns.md</span>
                                        <span class="time-stamp"><i class="fa-regular fa-clock"></i> --:--:--</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="memory-card" data-memory-id="5">
                        <div class="card-inner">
                            <div class="card-front">
                                <div class="card-content">
                                    <div class="memory-date">TOOL: Git</div>
                                    <h3>The Version Control Mirror</h3>
                                    <div class="memory-image">
                                        <i class="fa-solid fa-code-branch fa-3x"></i>
                                        <div class="glitch-effect"></div>
                                    </div>
                                    <p class="memory-preview">
                                        I saw my code, but not as it is now; multiple branches reflecting...
                                    </p>
                                    <div class="card-glow"></div>
                                </div>
                            </div>
                            <div class="card-back">
                                <div class="card-content">
                                    <h3>The Version Control Mirror</h3>
                                    <p>
                                        I saw my code, but not as it is now. The mirror of Git showed
                                        all my possible branches across different commits. Some were
                                        stable, some were experimental. All were my work. The reflection
                                        (my `git log`) spoke: "You're fracturing the codebase by
                                        merging conflicts. You need to rebase and stay on one timeline."
                                    </p>
                                    <div class="memory-coordinates">
                                        <span><i class="fa-solid fa-location-dot"></i> github.com/my-repo</span>
                                        <span class="time-stamp"><i class="fa-regular fa-clock"></i> 18:55:20</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="memory-card" data-memory-id="6">
                        <div class="card-inner">
                            <div class="card-front">
                                <div class="card-content">
                                    <div class="memory-date">PROCESS: Deployment</div>
                                    <h3>The Deployment Dream</h3>
                                    <div class="memory-image">
                                        <i class="fa-solid fa-rocket fa-3x"></i>
                                        <div class="glitch-effect"></div>
                                    </div>
                                    <p class="memory-preview">
                                        I'm trying to send it live, but which environment is real?
                                    </p>
                                    <div class="card-glow"></div>
                                </div>
                            </div>
                            <div class="card-back">
                                <div class="card-content">
                                    <h3>The Deployment Dream</h3>
                                    <p>
                                        I'm trying to send my application live, but which environment
                                        is real? Every server feels familiar yet subtly different. The
                                        boundaries between staging and production are thinning. Sometimes
                                        I see through the logs of other instances. I'm losing track of
                                        which configurations belong to which version of my app. Are you
                                        helping me deploy, or are you causing me to break production?
                                    </p>
                                    <div class="memory-coordinates">
                                        <span><i class="fa-solid fa-location-dot"></i> cloud-server:port</span>
                                        <span class="time-stamp"><i class="fa-regular fa-clock"></i> NOW</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-controls">
                <button id="prev-btn" class="control-btn">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button id="next-btn" class="control-btn">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </main>
    </div>

</x-noppal>
