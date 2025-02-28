<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>5 Seiten mit jQuery Navigation</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html { width: 100%; height: 100%; overflow: hidden; }
        .container { width: 500vw; height: 100vh; display: flex; position: relative; }
        .page { 
            width: 100vw; 
            height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            font-size: 2rem; 
            color: white;
            flex-shrink: 0;
        }
        .page:nth-child(1) { background: red; }
        .page:nth-child(2) { background: blue; }
        .page:nth-child(3) { background: green; }
        .page:nth-child(4) { background: orange; }
        .page:nth-child(5) { background: purple; }
        .nav-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }
        .nav-btn { 
            background: rgba(0,0,0,0.5); 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            cursor: pointer;
        }
        .loading {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 20px;
            border-radius: 5px;
        }
        .restart {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page" id="page1">Seite 1</div>
        <div class="page" id="page2">Seite 2</div>
        <div class="page" id="page3">Seite 3</div>
        <div class="page" id="page4">Seite 4</div>
        <div class="page" id="page5">Seite 5</div>
    </div>

    <div class="nav-container">
        <button class="nav-btn prev">Zurück</button>
        <button class="nav-btn next">Weiter</button>
        <button class="nav-btn restart">Erste Seite</button>
    </div>

    <div class="loading">Lade...</div>

    <script>
        $(document).ready(function() {
            let currentPage = 1;
            let totalPages = 5;

            function scrollToPage(page) {
                let position = -(page - 1) * 100 + "vw";
                $(".container").animate({ marginLeft: position }, 600);
                updateButtons(page);
            }

            function sendAjaxRequest(page, callback) {
                $(".loading").fadeIn();
                $.post("/nextPage", { page: page })
                    .done(function(response) {
                        $(".loading").fadeOut();
                        if (response.success) {
                            callback(true);
                        } else {
                            alert("Fehler beim Laden der Seite " + page);
                            callback(false);
                        }
                    })
                    .fail(function() {
                        $(".loading").fadeOut();
                        console.warn("Kein echter Server vorhanden, verwende Fallback für Seite " + page);
                        callback(true); // Falls kein Server da ist, gehe trotzdem weiter
                    });
            }

            function updateButtons(page) {
                if (page === 1) {
                    $(".prev").hide();
                } else {
                    $(".prev").show();
                }

                if (page === totalPages) {
                    $(".restart").show();
                } else {
                    $(".restart").hide();
                }
            }

            $(".next").click(function() {
                if (currentPage < totalPages) {
                    sendAjaxRequest(currentPage + 1, function(success) {
                        if (success) {
                            currentPage++;
                            scrollToPage(currentPage);
                        }
                    });
                }
            });

            $(".prev").click(function() {
                if (currentPage > 1) {
                    sendAjaxRequest(currentPage - 1, function(success) {
                        if (success) {
                            currentPage--;
                            scrollToPage(currentPage);
                        }
                    });
                }
            });

            $(".restart").click(function() {
                sendAjaxRequest(1, function(success) {
                    if (success) {
                        currentPage = 1;
                        scrollToPage(currentPage);
                    }
                });
            });

            updateButtons(currentPage);
        });
    </script>
</body>
</html> 