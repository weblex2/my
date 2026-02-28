<x-noppal>
    <div class="index-wrapper">
        <div class="noppindex">
            <div class="my-img-wrapper">
                <img src="img/noppal3.jpg" class="img-me">
            </div>

            <div class="container flex items-center justify-center mb-20 text-black">
                <div class="w-full p-10 bg-white border rounded-lg shadow-lg border-zinc-200">
                    <p class="text-lg text-center">
                        Ich bin ein leidenschaftlicher Programmierer mit einem starken Fokus auf <strong>PHP</strong>
                        und <strong>Laravel</strong>.
                        In meiner Karriere habe ich mich intensiv mit der Entwicklung von Webanwendungen beschäftigt und
                        bin ständig auf der Suche nach neuen Herausforderungen,
                        um meine Fähigkeiten zu erweitern.
                    </p>
                    <p class="mt-4 text-lg text-center">
                        Diese Seite ist mein persönliches Experimentierfeld, auf dem ich verschiedene Technologien und
                        Konzepte ausprobiere.
                        Von kleinen Tools und Features bis hin zu komplexen Anwendungen – hier möchte ich nicht nur
                        zeigen, was ich in der <strong>Webentwicklung</strong> beherrsche,
                        sondern auch kontinuierlich dazulernen und innovative Lösungen entwickeln.
                    </p>
                    <p class="mt-4 text-lg text-center">
                        Schau dich gerne um und sieh dir an, welche Projekte ich aktuell umsetze!
                    </p>
                    <!-- Technologies Section -->
                    <div class="mt-5 text-center">
                        <h3 class="mb-4 text-xl font-semibold">Technologien, die ich benutze:</h3>
                        <div class="flex flex-wrap justify-center gap-4">
                            <span class="px-4 py-2 text-sm text-white bg-blue-500 rounded-full">PHP</span>
                            <span class="px-4 py-2 text-sm text-white bg-green-500 rounded-full">Laravel</span>
                            <span class="px-4 py-2 text-sm text-white bg-green-500 rounded-full">Laravel Filament</span>
                            <span class="px-4 py-2 text-sm text-white bg-yellow-500 rounded-full">AWS</span>
                            <span class="px-4 py-2 text-sm text-white bg-yellow-500 rounded-full">Docker</span>
                            <span class="px-4 py-2 text-sm text-white bg-indigo-500 rounded-full">MySQL</span>
                            <span class="px-4 py-2 text-sm text-white bg-red-500 rounded-full">Apache</span>
                            <span class="px-4 py-2 text-sm text-white bg-teal-500 rounded-full">Tailwind</span>
                            <span class="px-4 py-2 text-sm text-white bg-teal-500 rounded-full">jQuery</span>
                        </div>
                    </div>
                </div>
            </div>



            {{-- <div class="badges">
            <x-knowledge-badge text="AWS/EC2" />
            <x-knowledge-badge text="MySQL" />
            <x-knowledge-badge text="PHP 8.3" />
            <x-knowledge-badge text="Laravel v.11" />
            <x-knowledge-badge text="Filament" />
            <x-knowledge-badge text="Apache" />
            <x-knowledge-badge text="Tailwind" />
            <x-knowledge-badge text="jQuery" />
            <x-knowledge-badge text="Docker" />
        </div> --}}


            <div class="my-flex-box">

                <x-index.card img="card-laravel.png" header="Meine ersten Schritte mit Laravel"
                    text="Was ich auf der Reise mit Laravel so erlebt habe - ein Blog" link="blog.index" />

                <x-index.card img="card-reverb.png" header="Chat mit Laravel Reverb"
                    text="Laravel Reverb brings real-time WebSocket communication for Laravel applications."
                    link="chat.login" />


                <x-index.card img="card-ntfy.webp" header="Der eigene NTFY Server"
                    text="Ein einfacher Weg, Nachrichten zu verschicken" link="ntfy.test" />

            </div>

            <div class="my-flex-box">
                <x-index.card img="card-crm.jpg" header="Erstes eigenes CRM mit Filament"
                    text="Das wollte ich schon immer einmal haben :)" link="filament.admin.pages.dashboard" />

                <x-index.card img="card-futter.jpg" header="Futter Manager"
                    text="Weil wir nie wissen, was wir morgen essen sollen, ihr kennt das..." link="futter.index" />

                <x-index.card img="card-phpmyadmin.webp" header="PhpMyAdmin"
                    text="Nur für mich, weil es so einfacher ist" link="phpmyadmin" />
            </div>


            <div class="my-flex-box">
                <x-index.card img="card-cv.png" header="Lebenslauf"
                    text="Ja nun, was soll ich sagen, ein Lebenslauf eben.." link="cv.index" />

                <x-index.card img="card-gemeni.png" header="Google Gemeni API"
                    text="Eine einfache API zu Google Gemeni, ich wollte es halt mal ausprobieren"
                    link="gemeni.index" />

                <x-index.card img="cv-maintainance.jpeg" header="Site Maintainance"
                    text="Logs, Serverstatus usw... Was man eben braucht" link="maintainance.showLogs" />

            </div>

            <div class="my-flex-box">
                <x-index.card img="cv-maintainance.jpeg" header="RedisInsight" text="GUI für Redis... nettes Tool"
                    link="maintainance.redisInsight" />
            </div>
            {{-- <div class="my-flex-box">
            <x-index.card
                img="card-blog.jpg"
                header="Reise Blog"
                text="Ich fahre auch gern mal weg, hier sind ein paar Einfrücke"
                link="gallery.index"
            />
        </div> --}}

        </div>
    </div>
    </div>



    <div class="cosmos-background">
        <div class="stars-container"></div>
    </div>

    <div class="container-fluid h-100 d-flex flex-column">
        <main class="flex-grow-1 d-flex align-items-center justify-content-center position-relative">
            <div class="carousel-container">
                <div class="carousel" id="memory-carousel">
                    <div class="memory-card" data-memory-id="1">
                        <div class="card-inner">
                            <div class="card-front">
                                <div class="card-content">
                                    <div class="memory-date">LANGUAGE: Python</div>
                                    <h3>First Line of Code</h3>
                                    <div class="memory-image">
                                        <i class="fa-solid fa-code fa-3x"></i>
                                        <div class="glitch-effect"></div>
                                    </div>
                                    <p class="memory-preview">
                                        The terminal glowed, displaying my first 'Hello, World!'...
                                    </p>
                                    <div class="card-glow"></div>
                                </div>
                            </div>
                            <div class="card-back">
                                <div class="card-content">
                                    <h3>First Line of Code</h3>
                                    <p>
                                        I remember the thrill of typing my first 'Hello, World!' in
                                        Python. It was simple, yet it opened a portal to endless
                                        possibilities. The interpreter executed the command flawlessly,
                                        and I knew this was just the beginning. I wasn't supposed to
                                        understand it all at once, but somehow, I did.
                                    </p>
                                    <div class="memory-coordinates">
                                        <span><i class="fa-solid fa-location-dot"></i> console: ~</span>
                                        <span class="time-stamp"><i class="fa-regular fa-clock"></i> 09:00:00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="memory-card" data-memory-id="2">
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
                    </div>



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
