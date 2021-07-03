(function () {

    const $html = document.querySelector('html');
    const $menuNav = document.querySelector('.menu__nav');
    const $usersAlert = document.querySelector("#users-alert");
    const $firstAdd = document.querySelector('#firstadd');
    const $footer = document.querySelector('.footer');
    const $help = document.querySelector('.help');
    const $helpYes = document.querySelector('.help__yes');
    const $helpNo = document.querySelector('.help__no');
    let scriptRedCards = "js/scripts-red-cards.js";
    let scriptGraphShow = "js/scripts-graph-show.js";
    let scriptSelect = "js/scripts-select.js";
    let scriptCourse = "js/scripts-course.js";


    //Toggle menu
    if ($html && $connect) {

        $html.addEventListener('click', function (event) {
            let eTarget = event.target;
            let eNav = eTarget.closest('#menu__nav');
            if (eNav !== null) {
                if (eNav.id != "menu__label") {
                    $menuNav.classList.remove('hide');
                } else {
                    $menuNav.classList.add('hide');
                }
            } else {
                if (eTarget.id == 'menu__label') {
                    $menuNav.classList.remove('hide');
                } else {
                    $menuNav.classList.add('hide');
                }
            }
        }, true);

    }


    function footerInnerHtml(scriptSrc) {
        //Add a script in footer

        let s = document.createElement("script");
        s.src = scriptSrc;
        $footer.innerHTML = "";
        $footer.appendChild(s);
    }
    if ($firstAdd) {
        saveAdd()
        footerInnerHtml(scriptSelect);
    }
    if ($usersAlert) {
        footerInnerHtml(scriptRedCards);
        if (!localStorage.getItem('isHelp')) {
            $help.classList.remove('hide');
        }
        $helpYes.addEventListener('click', function (event) {
            $help.classList.add('hide');
        });
        $helpNo.addEventListener('click', function (event) {
            localStorage.setItem('isHelp', true);
            $help.classList.add('hide');
        });
    }


    function saveAdd() {
        // Saves the data from the form for adding a course

        let $save = document.querySelector('#add-course');
        if ($save && (typeof $save !== "undefined")) {
            $save.addEventListener('click', function (e) {
                select = document.getElementById('selected-course');
                choice = select.selectedIndex;
                valueChoice = select.options[choice].getAttribute('data-id');
                e.preventDefault();
                $(".loading").fadeIn(); //show when submitting
                $.post(
                    'index.php', {
                    p: 'save/add',
                    course_name: $("#selected-course").val(),
                    course_id: valueChoice,
                    start_date: $("#start_date").val(),
                    end_date: $("#end_date").val(),
                    limit_yellow_card: $("#limit_yellow_card").val(),
                    evaluation_period: $("#evaluation_period").val(),
                },
                    function (data) {
                        $("#main").html(data);
                    },
                    'text'
                ).done(function () {
                    let saveCourseButton = document.querySelector('.save-event');
                    let courseName = saveCourseButton.getAttribute('data-id').replace(/\\/g, '');
                    let courseId = saveCourseButton.value;
                    courseList.add({
                        name: courseName,
                        reference: courseId
                    });
                    courseList.sort('name', {
                        order: "asc"
                    });
                    formEdit();
                    formDelete();
                    formUpdate();
                    displayWeeklyRecap();
                    next();
                    $(".loading").fadeOut('fast'); //hide when data's ready
                });
            }, false);
        }
    }


    function saveEdit() {
        // Saves the data from the form for editing a course

        let $edit = document.querySelector('#edit-course');
        if ($edit && (typeof $edit !== "undefined")) {
            $edit.addEventListener('click', function (e) {
                e.preventDefault();
                $(".loading").fadeIn(); //show when submitting
                $.post(
                    'index.php', {
                    p: 'save/edit',
                    course_id: $("#course_id").val(),
                    course_name: $("#course_name").val(),
                    start_date: $("#start_date").val(),
                    end_date: $("#end_date").val(),
                    limit_yellow_card: $("#limit_yellow_card").val(),
                },
                    function (data) {
                        $("#main").html(data);
                        next();
                        $(".loading").fadeOut('fast'); //hide when data's ready
                    },
                    'text'
                );
            }, false);
        }
    }


    function saveDelete() {
        // Saves the data from the form for deleting a course

        let $delete = document.querySelector('#delete-course');
        if ($delete && (typeof $delete !== "undefined")) {
            $delete.addEventListener('click', function (e) {
                let courseId = $(this).val();
                let courseName = $(this).data('id');
                e.preventDefault();
                $(".loading").fadeIn(); //show when submitting
                $.post(
                    'index.php', {
                    p: 'save/delete',
                    course_id: courseId,
                    course_name: courseName,
                },
                    function (data) {
                        $("#main").html(data);
                    },
                    'text'
                ).done(function () {
                    courseName = courseName.replace(/\\/g, '');
                    courseList.remove("name", courseName);
                    next();
                    redCardAlertUpdate();
                    $(".loading").fadeOut('fast'); //hide when data's ready
                });
            }, false);
        }
    }


    function saveUpdate() {
        // Saves the data from the form for updating a course

        let $update = document.querySelector('#update-course');
        if ($update && (typeof $update !== "undefined")) {
            $update.addEventListener('click', function (e) {
                let courseId = $(this).val();
                let courseName = $(this).data('id');
                e.preventDefault();
                $(".loading").fadeIn(); //show when submitting
                $.post(
                    'index.php', {
                    p: 'save/update',
                    course_id: courseId,
                    course_name: courseName,
                },
                    function (data) {
                        $("#main").html(data);
                        next();
                        $(".loading").fadeOut('fast'); //hide when data's ready
                    },
                    'text'
                );
            });
        }
    }


    function cancel() {
        // Cancel the action

        let $edit = document.querySelector('.canceled');
        if ($edit && (typeof $edit !== "undefined")) {
            $edit.addEventListener('click', function (e) {
                e.preventDefault();
                $(".loading").fadeIn(); //show when submitting
                $.post(
                    'index.php', {
                    p: 'cards/index',
                },
                    function (data) {
                        $("#main").html(data);
                        next();
                        $(".loading").fadeOut('fast'); //hide when data's ready
                    },
                    'text'
                );
            }, false);
        }
    }


    function displayHistory() {
        // Display the history of a course

        let $voirHistorique = document.querySelector('#history');
        if ($voirHistorique && (typeof $voirHistorique !== "undefined")) {
            $voirHistorique.addEventListener('click', function (e) {
                let ref = this.parentNode.querySelector('.reference').innerText;
                e.preventDefault();
                $(".loading").fadeIn(); //show when submitting
                $.post(
                    'index.php', {
                    p: 'history/index',
                    course_id: ref,
                },
                    function (data) {
                        $("#main").html(data);
                        footerInnerHtml(scriptGraphShow);
                        displayWeeklyRecap();
                        graphicFilter();
                        $(".loading").fadeOut('fast'); //hide when data's ready
                    },
                    'text'
                );
            }, false);
        }
    }

    function displayGraphStudent() {
        // Display the history of a course on a specific group or student

        let $groupGraphic = document.querySelectorAll('.group-graphic');
        if ($groupGraphic && (typeof $groupGraphic !== "undefined")) {
            for (let i = 0; i < $groupGraphic.length; i++) {
                $groupGraphic[i].addEventListener('click', function (e) {
                    let group = this.getAttribute('data-id');
                    let courseId = this.parentNode.querySelector('.reference').innerText;
                    e.preventDefault();
                    $(".loading").fadeIn(); //show when submitting
                    $.post(
                        'index.php', {
                        p: 'history/index',
                        course_id: courseId,
                        group: group,
                    },
                        function (data) {
                            $("#main").html(data);
                            footerInnerHtml(scriptGraphShow);
                            graphicFilter();
                            displayWeeklyRecap();
                            $(".loading").fadeOut('fast'); //hide when data's ready
                        },
                        'text'
                    );
                }, false);
            }
        }
        let $studentNote = document.querySelectorAll('.student-graphic');
        if ($studentNote && (typeof $studentNote !== "undefined")) {
            for (let i = 0; i < $studentNote.length; i++) {
                $studentNote[i].addEventListener('click', function (e) {
                    let courseId = this.parentNode.parentNode.querySelector('.reference').innerText;
                    let student = this.getAttribute('data-id');
                    e.preventDefault();
                    $(".loading").fadeIn(); //show when submitting
                    $.post(
                        'index.php', {
                        p: 'history/index',
                        course_id: courseId,
                        student: student,
                    },
                        function (data) {
                            $("#main").html(data);
                            footerInnerHtml(scriptGraphShow);
                            graphicFilter();
                            displayWeeklyRecap();
                            $(".loading").fadeOut('fast'); //hide when data's ready
                        },
                        'text'
                    );
                }, false);
            }
        }
    }


    function cardResolved() {
        // Resolved red card

        let $saveCarton = document.querySelector('#card-resolved');
        if ($saveCarton && (typeof $saveCarton !== "undefined")) {
            $saveCarton.addEventListener('click', function (e) {
                e.preventDefault();
                $.post(
                    'index.php', {
                    p: 'cards/resolved',
                    id_yellow_card: $(this).val(),
                },
                    function (data) {
                        $("#main").html(data);
                        redCardAlertUpdate();
                        displayCardDetails();
                        footerInnerHtml(scriptRedCards);
                        displayGraphStudent();
                        displayWeeklyRecap();
                    },
                    'text'
                );
            }, false);
        }
    }


    function cardNotResolved() {
        // Not resolved red card

        let $conserveCarton = document.querySelector('#card-not-resolved');
        if ($conserveCarton && (typeof $conserveCarton !== "undefined")) {
            $conserveCarton.addEventListener('click', function (e) {
                e.preventDefault();
                $.post(
                    'index.php', {
                    p: 'cards/notResolved',
                    id_yellow_card: $(this).val(),
                },
                    function (data) {
                        $("#main").html(data);
                        footerInnerHtml(scriptRedCards);
                        displayCardDetails();
                        redCardAlertUpdate();
                        displayGraphStudent();
                        displayWeeklyRecap();
                    },
                    'text'
                );
            }, false);
        }
    }


    function redCardAlertUpdate() {
        // Update red card alert button

        $.post(
            'index.php', {
            p: 'cards/redCardAlert',
        },
            function (data) {
                $(".header__alert").html(data);
                displayCardList();
            },
            'text'
        );
    }


    function next() {
        // Go to the red card alert list

        let $saveEvent = document.querySelectorAll('.save-event');
        if ($saveEvent && (typeof $saveEvent !== "undefined")) {
            for (let i = 0; i < $saveEvent.length; i++) {
                $saveEvent[i].addEventListener('click', function (e) {
                    e.preventDefault();
                    $.post(
                        'index.php', {
                        p: 'cards/index',
                    },
                        function (data) {
                            $("#main").html(data);
                            footerInnerHtml(scriptRedCards);
                            displayCardList();
                            displayCardDetails();
                        },
                        'text'
                    )
                }, false);
            };
        }
    }


    function graphicFilter() {
        // Filter the graph with groups and students

        let $filter = document.querySelector('#filter');
        if ($filter && (typeof $filter !== "undefined")) {
            $filter.addEventListener('click', function (e) {
                e.preventDefault();
                $.post(
                    'index.php', {
                    p: 'history/display',
                    course_id: $('#course_id').val(),
                    group: $('#group').val(),
                    student: $('#student').val(),
                },
                    function (data) {
                        $("#display").html(data);
                    },
                    'text'
                );
            }, false);
        }
    }


    function displayCardList() {
        // Display the red card list

        let $buttonRedCards = document.querySelectorAll('.button--red-cards');
        if ($buttonRedCards && (typeof $buttonRedCards !== "undefined")) {
            for (let i = 0; i < $buttonRedCards.length; i++) {
                $buttonRedCards[i].addEventListener('click', function (e) {
                    e.preventDefault();
                    $.post(
                        'index.php', {
                        p: 'cards/index',
                    },
                        function (data) {
                            $("#main").html(data);
                            footerInnerHtml(scriptRedCards);
                            displayCardDetails();
                            displayGraphStudent();
                            displayWeeklyRecap();
                        },
                        'text'
                    );
                }, false);
            }
        }
    }


    function formEdit() {
        // Display the edit form

        let $buttonEdit = document.querySelectorAll('.button--edit');
        if ($buttonEdit && (typeof $buttonEdit !== "undefined")) {
            for (var i = 0; i < $buttonEdit.length; i++) {
                $buttonEdit[i].addEventListener('click', function (e) {
                    $menuNav.classList.add('hide');
                    let ref = this.parentNode.querySelector('.reference').innerText;
                    e.preventDefault();
                    $.post(
                        'index.php', {
                        p: 'form/edit',
                        course_id: ref
                    },
                        function (data) {
                            $("#main").html(data);
                            saveEdit();
                            cancel();
                        },
                        'text'
                    );
                }, false);
            }
        }
    }

    function formAdd() {
        // Display the edit form

        let $buttonAdd = document.querySelectorAll('.button--add');
        if ($buttonAdd && (typeof $buttonAdd !== "undefined")) {
            for (var i = 0; i < $buttonAdd.length; i++) {
                $buttonAdd[i].addEventListener('click', function (e) {
                    $menuNav.classList.add('hide');
                    // let ref = this.parentNode.querySelector('.reference').innerText;
                    e.preventDefault();
                    $(".loading").fadeIn(); //show when submitting
                    $.post(
                        'index.php', {
                        p: 'form/add',
                    },
                        function (data) {
                            $("#main").html(data);
                            $(".loading").fadeOut('fast'); //hide when data's ready
                            footerInnerHtml(scriptSelect);
                            saveAdd();
                            cancel();
                        },
                        'text'
                    );
                }, false);
            }
        }
    }

    function formDelete() {
        // Display the delete form

        let $buttonDelete = document.querySelectorAll('.button--delete');
        if ($buttonDelete && (typeof $buttonDelete !== "undefined")) {
            for (let i = 0; i < $buttonDelete.length; i++) {
                $buttonDelete[i].addEventListener('click', function (e) {
                    $menuNav.classList.add('hide');
                    let ref = this.parentNode.querySelector('.reference').innerText;
                    e.preventDefault();
                    $.post(
                        'index.php', {
                        p: 'form/delete',
                        course_id: ref
                    },
                        function (data) {
                            $("#main").html(data);
                            saveDelete();
                            cancel();
                        },
                        'text'
                    );
                }, false);
            }
        }
    }


    function formUpdate() {
        // Display the update form

        let $buttonUpdate = document.querySelectorAll('.button--update');
        if ($buttonUpdate && (typeof $buttonUpdate !== "undefined")) {
            for (let i = 0; i < $buttonUpdate.length; i++) {
                $buttonUpdate[i].addEventListener('click', function (e) {
                    $menuNav.classList.add('hide');
                    let ref = this.parentNode.querySelector('.reference').innerText;
                    e.preventDefault();
                    $.post(
                        'index.php', {
                        p: 'form/update',
                        course_id: ref,
                    },
                        function (data) {
                            $("#main").html(data);
                            saveUpdate();
                            cancel();
                        },
                        'text'
                    );
                }, false);
            }
        }
    }


    function sendMail() {
        // Send a reminder by email

        let $createMail = document.querySelector('#send-mail');
        if ($createMail && (typeof $createMail !== "undefined")) {
            $createMail.addEventListener('click', function (e) {
                let courseId = this.getAttribute('data-id');
                e.preventDefault();
                $(".loading").fadeIn(); //show when submitting
                $.post(
                    'index.php', {
                    p: 'weekly_recap/emailReminder',
                    course_id: courseId,
                },
                    function (data) {
                        $("#main").html(data);
                        footerInnerHtml(scriptCourse);
                        displayGraphStudent();
                        $(".loading").fadeOut('fast'); //hide when data's ready
                    },
                    'text'
                );
            });
        }
    }

    function displayWeeklyRecap() {
        // Display the weekly recap 

        let $buttonShow = document.querySelectorAll('.button--show');
        if ($buttonShow && (typeof $buttonShow !== "undefined")) {
            for (let i = 0; i < $buttonShow.length; i++) {
                $buttonShow[i].addEventListener('click', function (e) {
                    $menuNav.classList.add('hide');
                    let ref = this.parentNode.querySelector('.reference').innerText;
                    e.preventDefault();
                    $(".loading").fadeIn(); //show when submitting
                    $.post(
                        'index.php', {
                        p: 'weekly_recap/index',
                        course_id: ref,
                    },
                        function (data) {
                            $("#main").html(data);
                            footerInnerHtml(scriptCourse);
                            displayHistory();
                            sendMail();
                            displayGraphStudent();
                            displayCardDetails();
                            $(".loading").fadeOut('fast'); //hide when data's ready
                        },
                        'text'
                    );
                }, false);
            }
        }
    }


    function displayCardDetails() {
        // Display the details of a red card

        let $cartonRouge = document.querySelectorAll('.red-card');
        if ($cartonRouge && (typeof $cartonRouge !== "undefined")) {
            for (let i = 0; i < $cartonRouge.length; i++) {
                $cartonRouge[i].addEventListener('click', function (e) {
                    e.preventDefault();
                    $.post(
                        'index.php', {
                        p: 'cards/details',
                        id_red_card: $(this).val(),
                    },
                        function (data) {
                            $("#main").html(data);
                            next();
                            cardResolved();
                            cardNotResolved();
                            // displayCardDetails();
                            // redCardAlertUpdate();
                        },
                        'text'
                    );
                }, false);
            };
        }
    }


    function initDashboard() {
        // Init the dashboard with required functions

        // Form function
        formAdd();
        formEdit();
        formDelete();
        formUpdate();
        // Weekly recap function
        displayWeeklyRecap();
        // Script
        // footerInnerHtml(scriptSelect);
        // Red card function
        displayCardList();
        displayCardDetails();
        // Graphic function
        displayGraphStudent();
    }

    initDashboard();
})();