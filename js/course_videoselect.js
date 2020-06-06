// wacht to het document geladen is
$(document).ready(function() {

    // haal het course id op
    const courseID = $("body").attr("data-course-id");

    // als er op een video selectie knop wordt geklikt
    $(".video-select-btn").click(function() {
        
        // haal het id op van de video
        let videoID = $(this).attr("data-video-num");
        let hoofdstukID = $(this).parent().parent().attr("data-hoofdstuk-num");

        // api call
        $.ajax({
            dataType: "json",
            method: "POST",
            url: "api/getvideodata.php",
            data: {
                "course": courseID,
                "hoofdstuk": hoofdstukID,
                "video": videoID
            },
            success: function ( response ) {
                // succes:

                // test of er geen database error is
                if (response.success === true) {

                    // haal de actieve selectie weg
                    $(".video-actief").removeAttr("disabled");
                    $(".hoofdstuk-actief").removeClass("hoofdstuk-actief");
                    $(".video-actief").removeClass("video-actief");

                    // selecteer de nieuwe video
                    $("#headingHoofdstuk" + hoofdstukID).addClass("hoofdstuk-actief");
                    $("#hoofdstuk" + hoofdstukID + " .video-select-btn[data-video-num='" + videoID + "']").addClass("video-actief");
                    $("#hoofdstuk" + hoofdstukID + " .video-select-btn[data-video-num='" + videoID + "']").attr("disabled", "disabled");

                    // zet de gegevens aan de linkerkant
                    $("#course-currentvideo").attr("src", "https://www.youtube.com/embed/" + response.data.url);
                    $("#course-currentvideo-title").text("Video " + videoID + " - " + response.data.titel);
                    $("#omschrijving").text(response.data.omschrijving);
                }

            }
        });

    });

    // selecteer automatisch de eerste video
    $(".video-select-btn")[0].click();
    // open de eerste dropdown
    $("[data-hoofdstuk-num=1]").collapse("show");

});