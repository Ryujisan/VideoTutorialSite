$(document).ready(function () {
    // haal het id van de course op
    let courseId = $("body").attr("data-course-id");

    // initieer de pagina
    reloadCourseData();
    reloadCourseContents();

    // functie om youtube urls te valideren en het id uit het url te halen
    function getYoutubeId(url) {
        var regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        var match = url.match(regExp);
        if (match && match[2].length == 11) {
            return match[2];
        } else {
            // error
            return false;
        }
    }

    // functie die de gegevens van de course herlaad
    function reloadCourseData() {
        $.ajax({
            dataType: "json",
            method: "POST",
            url: "api/getcoursedata.php",
            data: {
                course: courseId,
            },
            success: function (response) {
                // succes:

                // test of er geen database error is
                if (response.success === true) {
                    // zet de waardes in de iinput velden
                    $("#input-coursetitel").val(response.data.titel);
                    $("#input-coursezichtbaarheid").val(response.data.zichtbaarheid);
                    $("#input-courseomschrijving").val(response.data.omschrijving);
                }
            },
        });
    }

    // functie die de inhoud van de course herlaad
    function reloadCourseContents() {
        let htmlData = "";

        // api call
        $.ajax({
            dataType: "json",
            method: "POST",
            url: "api/getfullcoursecontents.php",
            data: {
                course: courseId,
            },
            success: function (response) {
                // succes:

                // test of er geen database error is
                if (response.success === true) {
                    // sla de hoofdstukken op in een variabel
                    let hoofdstukken = response["hoofdstukken"];

                    // maak een loopcount aan
                    let hoofdstukLoopCount = 0;
                    // loop door de hoofdstukken heen
                    Object.keys(hoofdstukken).forEach(function (hoofdstukKey) {
                        // voeg 1 toe aan de loopcount
                        hoofdstukLoopCount++;
                        // sla he hoofdstuk op in een variabel
                        let hoofdstuk = hoofdstukken[hoofdstukKey];

                        // voeg de opening van de html van het hoofdstuk kaartje toe
                        htmlData += `
                        <!-- Hoofdstuk ${hoofdstukKey} -->
                        <div class="card my-5 shadow-sm bg-light" data-hoofdstuk-num="${hoofdstukKey}">
                            <div class="card-header bg-vtsdarkgreen">
                                <div class="input-group position-relative">
                                    <div class="input-group-prepend">
                                        <!-- Chapter number -->
                                        <span class="input-group-text">${hoofdstukLoopCount}.</span>
                                    </div>
                                    <!-- Chapter name -->
                                    <input type="text" class="form-control form-control-lg" placeholder="Hoofdstuk" value="${hoofdstuk.titel}" data-inputdata="hoofdstuk-titel" required>
                                    <div class="invalid-tooltip"></div>
                                </div>
                            </div>
                            <div class="card-body">
                        `;

                        // sla de video's van dit hoofdstuk op in een variabel
                        let videos = hoofdstuk["videos"];

                        // maak een loopcount aan
                        let videoLoopCount = 0;
                        // loop door de video's van het hoofdstuk heen
                        Object.keys(videos).forEach(function (videoKey) {
                            // voeg 1 toe aan de video loopcount
                            videoLoopCount++;
                            // sla de video op in een variabel
                            let video = videos[videoKey];

                            // voeg de html voor de video toe aan de html data
                            htmlData += `
                                <!-- Video ${videoKey} -->
                                <div class="my-3" data-video-num="${videoKey}">
                                    <div class="input-group position-relative">
                                        <div class="input-group-prepend rounded-bottom-0">
                                            <!-- Videonummer -->
                                            <span class="input-group-text rounded-0">${videoLoopCount}</span>
                                        </div>
                                        <!-- Videonaam -->
                                        <input type="text" class="form-control" placeholder="Oefening" value="${video.titel}" data-inputdata="video-titel" required>
                                        <div class="invalid-tooltip"></div>
                                        <!-- Remove video button -->
                                        <button type="button" class="close verwijder-video" buttonType="removeVideo">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <!-- Video URL -->
                                    <div class="position-relative">
                                        <input type="text" class="form-control rounded-0" placeholder="URL" value="https://youtu.be/${video.url}" data-inputdata="video-url" required>
                                        <div class="invalid-tooltip"></div>
                                    </div>
                                    <!-- Video omschrijving -->
                                    <textarea type="text" class="form-control rounded-0" placeholder="Omschrijving" data-inputdata="video-omschrijving">${video.omschrijving}</textarea>
                                </div>
                                `;
                        });

                        // voeg de overige html toe aan de htmldata
                        htmlData += `
                                <div class="my-3 form-inline position-relative">
                                    <input type="text" class="form-control control-ignore rounded-0 col-8 col-md-10" data-addVideoUrl placeholder="Nieuw video-URL">
                                    <div class="invalid-tooltip"></div>
                                    <button type="button" class="btn btn-success bg-vtsgreen rounded-0 col-4 col-md-2" buttonType="addVideo">Voeg toe</button>
                                </div>
                            </div>
                            <div class="btn-group" role="group">
                                <!-- <button type="button" class="btn btn-secondary bg-vtslightgreen">Voeg bestanden toe toe</button> -->
                                <button type="button" class="btn btn-danger" buttonType="removeHoofdstuk">Verwijder hoofdstuk</button>
                            </div>
                        </div>
                        `;
                    });

                    // zet de data op de pagina
                    $("#course-content").html(htmlData);

                    // update de input events
                    updateInputs();
                    // update de button events
                    updateButtons();
                }
            },
        });
    }

    function updateInputs() {
        // test voor input changes
        $(".form-control[data-inputdata]").change(function () {
            // test of de input required is en een waarde heeft ingevuld of de control-ignore class heeft. of test of de input niet required is.
            if ($(this).attr("required") !== "required" || ($(this).attr("required") === "required" && ($(this).val().length > 0 || $(this).hasClass("control-ignore")))) {
                // test of inputs met video-url data gevalideerd kunen worden met de getyoutubeid functie, of test of de functie geen video-url input is
                if (($(this).attr("data-inputdata") === "video-url" && getYoutubeId($(this).val())) || $(this).attr("data-inputdata") !== "video-url") {
                    // clear de error text
                    $(this).next("invalid-tooltip").text("");
                    // haal de not validated class weg
                    $(this).removeClass("is-invalid");

                    // maak een leeg updateData object aan
                    let updateData = {
                        course: courseId,
                    };

                    // test of het veld een video veld is
                    if ($(this).parents("[data-video-num]").length === 1) {
                        // zet het veldtype gelijk aan video en zet het hoofdstuk nummer en video nummer
                        updateData.hoofdstuk = $(this).parents("[data-hoofdstuk-num]").attr("data-hoofdstuk-num");
                        updateData.video = $(this).parents("[data-video-num]").attr("data-video-num");
                        updateData.veldType = "video";
                    }
                    // test of het veld een hoofdstuk veld is
                    else if ($(this).parents("[data-hoofdstuk-num]").length === 1) {
                        // zet het veldtype gelijk aan hoofdstuk en zet het hoofdstuk nummer
                        updateData.hoofdstuk = $(this).parents("[data-hoofdstuk-num]").attr("data-hoofdstuk-num");
                        updateData.veldType = "hoofdstuk";
                    }
                    // test of dit een main info veld van de course is
                    else if ($(this).parents("#mainCourseInfo").length === 1) {
                        // zet het veldtype gelijk aan course
                        updateData.veldType = "course";
                    }

                    // voeg de data van de input toe aan de updatedata
                    updateData.veld = $(this).attr("data-inputdata");
                    updateData.waarde = $(this).attr("data-inputdata") === "video-url" ? getYoutubeId($(this).val()) : $(this).val();

                    // maak het veld readonly en voeg de is-loading class toe
                    $(this).attr("readonly", "readonly");
                    $(this).addClass("is-loading");

                    // sla het veld dat aangepast wordt op in een variabel zodat de ajax call er ook bij kan
                    let currentVeld = this;

                    // api call
                    $.ajax({
                        dataType: "json",
                        method: "POST",
                        url: "api/updatedata.php",
                        data: updateData,
                    })
                        .done(function (data) {
                            // als de api call succesvol is uitgevoerd:
                            // check of de data succesvol terug komt
                            if (!data.success) {
                                // de data is niet succesvol verwerkt in de database:
                                // laat een error zien op het veld
                                // zet de error message voeg de invalid-feedback class toe
                                $(currentVeld).next(".invalid-tooltip").text("Fout bij het verwerken van de data, probeer de pagina te verversen en log eventueel opnieuw in");
                                $(currentVeld).addClass("is-invalid");
                            }
                        })
                        .fail(function () {
                            // als de api call niet succesvol is uitgevoerd:
                            // laat een error zien op het veld
                            // zet de error message voeg de invalid-feedback class toe
                            $(currentVeld).next(".invalid-tooltip").text("Fout bij het versturen van de data, check je internet connectie");
                            $(currentVeld).addClass("is-invalid");
                        })
                        .always(function () {
                            // ALTIJD als het ajax statement is uitgevoerd:
                            // maak het veld aanpasbaar en haal de is-loading class weg
                            $(currentVeld).removeAttr("readonly");
                            $(currentVeld).removeClass("is-loading");
                        });
                } else {
                    // zet de error message voeg de invalid-feedback class toe
                    $(this).next(".invalid-tooltip").text("URL niet als een geldig youtube url erkend, niet geupdated");
                    $(this).addClass("is-invalid");
                }
            } else {
                // zet de error message voeg de invalid-feedback class toe
                $(this).next(".invalid-tooltip").text("Verplicht!, niet geupdated");
                $(this).addClass("is-invalid");
            }
        });
    }

    // functie om de button events te updaten
    function updateButtons() {
        // per button een andere verwerking
        // hoofdstuk verwijderen
        $("button[buttonType='removeHoofdstuk']").click(function () {
            // sla op welke button er in is geklikt
            let button = this;
            // disable de button
            $(button).attr("dissabled", "dissabled");

            // stel de verstuurdata in
            let verstuurData = {
                course: courseId,
                hoofdstuk: $(this).parents("[data-hoofdstuk-num]").attr("data-hoofdstuk-num"),
            };

            // api call
            $.ajax({
                dataType: "json",
                method: "POST",
                url: "api/removehoofdstuk.php",
                data: verstuurData,
            })
                .done(function (data) {
                    // als de api call succesvol is uitgevoerd:
                    // check of de data succesvol terug komt
                    if (data.success) {
                        // de data is succesvol verwerkt in de database:
                        // reload de page inhoud
                        reloadCourseContents();
                    } else {
                        // de data is niet succesvol verwerkt, laat een error zien
                        alert(data.error);
                    }
                })
                .fail(function () {
                    // als de api call niet succesvol is uitgevoerd:
                    alert("Fout bij het versturen van de data, check eventueel je internet connectie");
                })
                .always(function () {
                    // ALTIJD als het ajax statement is uitgevoerd:
                    // maak de button weer klikbaar
                    $(button).removeAttr("dissabled");
                });
        });
        // video verwijderen
        $("button[buttonType='removeVideo']").click(function () {
            // sla op welke button er in is geklikt
            let button = this;
            // disable de button
            $(button).attr("dissabled", "dissabled");

            // bepaal de data die verstuurd moet worden
            let verstuurData = {
                course: courseId,
                hoofdstuk: $(this).parents("[data-hoofdstuk-num]").attr("data-hoofdstuk-num"),
                video: $(this).parents("[data-video-num]").attr("data-video-num"),
            };

            // maak een api call
            $.ajax({
                dataType: "json",
                method: "POST",
                url: "api/removevideo.php",
                data: verstuurData,
            })
                .done(function (data) {
                    // als de api call succesvol is uitgevoerd:
                    // check of de data succesvol terug komt
                    if (data.success) {
                        // de data is succesvol verwerkt in de database:
                        // reload de page inhoud
                        reloadCourseContents();
                    } else {
                        // de data is niet succesvol verwerkt, laat een error zien
                        alert(data.error);
                    }
                })
                .fail(function () {
                    // als de api call niet succesvol is uitgevoerd:
                    alert("Fout bij het versturen van de data, check eventueel je internet connectie");
                })
                .always(function () {
                    // ALTIJD als het ajax statement is uitgevoerd:
                    // maak de button weer klikbaar
                    $(button).removeAttr("dissabled");
                });
        });
        // video toevoegen
        $("button[buttonType='addVideo']").click(function () {
            if ((url = getYoutubeId($(this).siblings("[data-addVideoUrl]").val()))) {
                // het is een youtube url:
                // clear de error
                $(this).siblings("[data-addVideoUrl]").next(".invalid-tooltip").text("");
                $(this).siblings("[data-addVideoUrl]").removeClass("is-invalid");

                // sla op welke button er in is geklikt
                let button = this;
                // disable de button
                $(button).attr("dissabled", "dissabled");

                // bepaal de data
                let verstuurData = {
                    course: courseId,
                    hoofdstuk: $(this).parents("[data-hoofdstuk-num]").attr("data-hoofdstuk-num"),
                    url: url,
                };
                $.ajax({
                    dataType: "json",
                    method: "POST",
                    url: "api/addvideo.php",
                    data: verstuurData,
                })
                    .done(function (data) {
                        // als de api call succesvol is uitgevoerd:
                        // check of de data succesvol terug komt
                        if (data.success) {
                            // de data is succesvol verwerkt in de database:
                            // reload de page inhoud
                            reloadCourseContents();
                        } else {
                            // de data is niet succesvol verwerkt, laat een error zien
                            alert(data.error);
                        }
                    })
                    .fail(function () {
                        // als de api call niet succesvol is uitgevoerd:
                        alert("Fout bij het versturen van de data, check eventueel je internet connectie");
                    })
                    .always(function () {
                        // ALTIJD als het ajax statement is uitgevoerd:
                        // maak de button weer klikbaar
                        $(button).removeAttr("dissabled");
                    });
            } else {
                // error als er geen youtube url wordt ingevuld
                $(this).siblings("[data-addVideoUrl]").next(".invalid-tooltip").text("URL niet als een geldig youtube url erkend");
                $(this).siblings("[data-addVideoUrl]").addClass("is-invalid");
            }
        });
    }

    // de knop om hoofdstukken toe te voegen wordt nooit gereplaced en hoeft dus ook niet in de updatebuttons functie
    $("button[buttonType='addHoofdstuk']").click(function () {
        let button = this;
        $(button).attr("dissabled", "dissabled");

        // stel de verstuurdata in
        let verstuurData = {
            course: courseId,
        };

        $.ajax({
            dataType: "json",
            method: "POST",
            url: "api/addhoofdstuk.php",
            data: verstuurData,
        })
            .done(function (data) {
                // als de api call succesvol is uitgevoerd:
                // check of de data succesvol terug komt
                if (data.success) {
                    // de data is succesvol verwerkt in de database:
                    // reload de page inhoud
                    reloadCourseContents();
                } else {
                    // de data is niet succesvol verwerkt, laat een error zien
                    alert(data.error);
                }
            })
            .fail(function () {
                // als de api call niet succesvol is uitgevoerd:
                alert("Fout bij het versturen van de data, check eventueel je internet connectie");
            })
            .always(function () {
                // ALTIJD als het ajax statement is uitgevoerd:
                // maak de button weer klikbaar
                $(button).removeAttr("dissabled");
            });
    });
});
