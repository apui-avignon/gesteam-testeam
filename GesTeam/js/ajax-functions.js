(function () {

    function displayHistory() {
        // Saves the data from the form for editing a course

        let $history = document.querySelector('#history');
        if ($history && (typeof $history !== "undefined")) {
            $history.addEventListener('click', function (e) {
                e.preventDefault();
                $(".loading").fadeIn(); //show when submitting
                $.post(
                    'index.php', {
                    p: 'history/index',
                },
                    function (data) {
                        $("#main").html(data);
                        $(".loading").fadeOut('fast'); //hide when data's ready
                    },
                    'text'
                );
            }, false);
        }
    }

    function initDashboard() {
        displayHistory();
    }

    initDashboard();
}) ();