import $ from 'jquery';
class Table {

    constructor() {
        // this.moveDescLeft();
        this.events();

    }

    events() {

        $("#my-table").on("click",".btn--add-arbayiin", this.editTable.bind(this));
        $(".btn--add-task").on("click", this.editTask.bind(this));
        $("#my-table").on("click", ".submit-arbayiin", this.createArbayiin.bind(this));
        $(".new-task").on("click", ".save-task", this.createTask.bind(this));
        $(".amal-js").on("click", this.showContentPopUp.bind(this));
        $(".resultvalue").on("click", this.showResultsPopUp.bind(this));
        $(".delete-lastday").on("click", this.dayDeleteDispatcher.bind(this));
        $(".pop-outer").on("click",".close-popup", this.closeContentPopUp.bind(this));
        $(".date-pop-outer").on("click","#btnAddAction", this.editStartDate.bind(this));
        $("#startdate").on("click", this.startDate.bind(this));
    }
    moveDescLeft() {
        var amaldesc = $(".amalDesc").text();
        var content = $(".amal-js").data("content");
        $("table").find('tr').each(function () {
            // console.log('content: ' + content);
        });
        $(".amal-js").each(function () {
            console.log('content: ' + content);
           if ($(this).data("content") != ''){

           }
        });
        console.log(content);
    }
    dayDeleteDispatcher(e) {
        var dateTitle = $(e.target).data('datetitle');
        var dayid = $(e.target).data('dayid');
        console.log(dayid);
        $("body").addClass("body-no-scroll");
        $(".pop-outer h5").html(`<span>` + `آیا برای حذف نتایج ` + dateTitle + ` مطمئن هستید؟` + `</span>`);
        $(".pop-inner p").html(``);
        $(".pop-inner p").html(`<div>` + `همه ی نتایج ` + dateTitle + ` حذف خواهند شد.` + `</div><br/><div class="cancel-delete btn btn--blue">انصراف</div>  <div class="confirm-delete btn btn--orange btn-outline-warning">حذف</div>`);
        $(".pop-outer").fadeIn("slow");
        $(".confirm-delete").on("click", {
            dayid : dayid
        }, this.deleteLastday.bind(this));
        // $(".confirm-delete").bind("click", $(e.target).data('dayid'), this.deleteLastday);

        $(".cancel-delete").on("click",  this.cancelDelete.bind(this));
    }

    cancelDelete(e) {
        $("body").removeClass("body-no-scroll");
        $(".pop-outer").fadeOut("slow");
    }

    deleteLastday(e) {
        // var dayid = $(".delete-lastday").data('dayid');
        var dayid = e.data.dayid;

        console.log(e.data.dayid);
        var data = {
            'dayid' : dayid
        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
            },
            url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/deleteLastDay',
            type: 'DELETE',
            data: data,
            success: (response) => {
                console.log("Congrats");
                console.log(response);
                location.reload();

            },
            error: (response) => {
                console.log("Sorry");
                console.log(response);
            }
        });


    }
    editStartDate(id) {
       var edit_window = $("#frmEdit").dialog({
            buttons: {
                "Edit": editComment
            },
            close: function() {

                edit_window.dialog( "close" );
            }
        });
        edit_window.dialog( "open" );
       var comment_id = id;
        var message = $("#message_" + comment_id + " .message-content").html();
        $("#edit-message").val(message);
    }

    startDate(e) {

        var month = [
            "فروردین",
            "اردیبهشت",
            "خرداد",
            "تیر",
            "مرداد",
            "شهریور",
            "مهر",
            "آبان",
            "آذر",
            "دی",
            "بهمن",
            "اسفند"
        ];
        var pelement = $(".date-pop-outer p");

        $(".date-pop-outer h5").html(`<span>تاریخ شروع: </span>`);
        pelement.html(`<span>ماه: </span><select class="select-date" name="amalresults"></select>`);
        $.each(month, function (i, elem) {
            $(".select-date").append(`<option >${elem}</option>`)
        });

        $(".date-pop-outer").fadeIn("slow");
    }

    showContentPopUp(e) {
        var thisAmal = $(e.target).closest(".amal-js");

        var content = thisAmal.data("content");
        $("body").addClass("body-no-scroll");
        var name = thisAmal.data("name");
        // alert(content);
        console.log(thisAmal);
        console.log($(e.target));
        console.log($(e.target).closest(".amal-js"));
        $(".pop-outer h5").html(`<span>توضیحات ${name}:</span>`);
        $(".pop-inner p").html(`<span>${content}</span>`);
        $(".pop-outer").fadeIn("slow");
    }

    closeContentPopUp(e) {
        $("body").removeClass("body-no-scroll");
        $(".pop-outer").fadeOut("slow");
    }

    showResultsPopUp(e) {
        var thisAmal = $(e.target).closest(".resultvalue");

        var name = thisAmal.data("name");
        var result = thisAmal.data("result");
        if (result == undefined) return;
        // $("body").addClass("body-no-scroll");
        // var name = thisAmal.data("name");
        // alert(content);
        console.log(thisAmal);
        console.log($(e.target));
        console.log($(e.target).closest(".amal-js"));
        $(".pop-outer h5").html(`<span>نتیجه ${name}:</span>`);
        $(".pop-inner p").html(`<span>${result}</span>`);
        $(".pop-outer").fadeIn("slow");
    }

    closeResultsPopUp(e) {
        $("body").removeClass("body-no-scroll");
        $(".pop-outer").fadeOut("slow");
    }



    editTable(e) {
        var thisNote = $(e.target).parents("li");
        if (thisNote.data("state") == "editable") {
            //make read only
            this.cancelNewTable(thisNote);
        } else {
            // make editable
            this.addNewTable(thisNote)
        }
    }

    addNewTable(thisNote) {
        thisNote.find(".btn--add-arbayiin").html('<i class="fa fa-times" aria-hidden="true">انصراف </i>');
        $(".new-table").addClass("new-table--visible").hide().slideDown();

        $("#my-table").find(".update-note").addClass("update-note--visible");
        thisNote.data("state", "editable");


        // $(".btn--add-arbayiin").html('<button class="btn--cancel-arbayiin" aria-hidden="true">انصراف</button>');
    }

    cancelNewTable(thisNote) {
        thisNote.find(".btn--add-arbayiin").html('<i class="fa" aria-hidden="true">+ افزودن اربعین جدید</i>');
        $("#my-table").find(".new-table").removeClass("new-table--visible").slideUp();
        $("#my-table").find(".update-note").removeClass("update-note--visible");
        thisNote.data("state", "cancel");

    }

    editTask(e) {

        var thisTask = $(e.target).closest(".btn--add-task");
        if (thisTask.data("state") == "editable") {
            //make read only
            this.cancelNewTask(thisTask);
        } else {
            // make editable
            this.addNewTask(thisTask)
        }
    }

    addNewTask(thisTask) {

        $(".btn--add-task").html(`انصراف<i class="fa inline" aria-hidden="true"></i>`);
        $(".new-task").addClass("new-task--visible").hide().slideDown();

        thisTask.data("state", "editable");
    }

    cancelNewTask(thisTask) {

$(".btn--add-task").html("+ افزودن عمل جدید<i class=\"fa\" aria-hidden=\"true\"></i>");
$(".new-task").removeClass("new-task--visible").hide().slideUp();


        thisTask.data("state", "cancel");
    }

    createArbayiin(e) {

        var ourNewArbayiin = {
            'title': $(".new-arbayiin-title").val(),
            'content': $(".new-arbayiin-body").val(),
            'duration': $(".duration").val(),
            'status': 'publish'

        }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
            },
            url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/manageArbayiin',
            type: 'POST',
            data: ourNewArbayiin,
            success: (response) => {
                $(".new-arbayiin-title, .new-arbayiin-body").val('');
                // $(`
                //      <li data-id="${response.id}">
                //     <a href="${response.link}">${response.title.raw}</a></li>
                // `).prependTo("#my-arbayiin").hide().slideDown();

                console.log("Congrats");
                console.log(response.link);
                console.log(response.id);
                console.log(response);
            },
            error: (response) => {
                console.log("Sorry");
                console.log(response);
            }
        });
    }

    createTask(e) {

        var thisTask = $(e.target).closest(".save-task");
        var taskcounts = thisTask.data('taskcounts');
        var ourNewTask = {
            'title': $(".new-task-title").val(),
            'content': $(".new-task-body").val(),
            'period': $(".period").val(),
            'arbayiinId': thisTask.data('arbayiin')

        }
        console.log(ourNewTask);
$.ajax({
    beforeSend: (xhr) => {
        xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
        },
    url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/manageTask',
    type: 'POST',
    data: ourNewTask,
    success: (response) => {
        console.log('Congratulationssss');
        console.log(response);
        $(".new-task-title , .new-task-body , .period").val('');
        },
    error: (response) => {
        console.log('sorry');
        console.log(response);
    }

});

        $("table tbody").append(`<tr>
                        <td>
                        <span class="inline dot">${taskcounts+1}</span>
                        </td>
                        <td>
                        <span>${ourNewTask['title']}</span>
                        </td>
                    </tr>`);

    // $(`<span class="inline dot">1</span><p class="inline">${title}</p>`).prependTo("#amal").hide().slideDown();
    this.cancelNewTask(thisTask);
    }

    ourClickDispatcher(e) {
        var currentLikeBox = $(e.target).closest(".like-box");

        if (currentLikeBox.attr('data-exists') == 'yes') {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }
}

export default Table;