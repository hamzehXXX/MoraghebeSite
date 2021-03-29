import $ from 'jquery';

class Amal {
    constructor() {
        this.events();
    }

    events() {

        $("#submit-amal").on("click",  this.sabtAmal.bind(this));
        $(".delete-results").on("click",  this.deleteDispatcher.bind(this));
        $("button#submit-date").on("click",  this.sabtDate.bind(this));
        $(".display-arbcontent").on("click", this.displayContent.bind(this));
        $("#startDate").on("click", this.startDateClick.bind(this));
        $("i#amuzeh").on("click", this.displayAmuzesh.bind(this));

    }

    startDateClick() {
        window.scrollTo(0,200);
    }
    displayContent() {
        // alert("heey");
        $("div#arb-content").toggleClass("hide");
        $("div#display-arbcontent").toggleClass("hide");
        $("div#arb-excerpt").toggleClass("hide");
    }

    displayAmuzesh() {
        $("ol#amuzesh-body").toggleClass("hide");

    }

    sabtDate() {
        var startDate = $("input.start-date").val();
        var userid = $("input.start-date").data("userid");
        var arbid = $("input.start-date").data("arbid");
        var optionName = userid + "-" + arbid;
        console.log(optionName);
        // alert(optionName);
        if (startDate == '') {
            $(".arbayiin-table, .generic-content").addClass("hide-table");
            $(".start-date__alert").text('لطفا تاریخ شروع را مشخص بفرمایید');
        } else {
            $(".arbayiin-table, .generic-content").removeClass("hide-table");
            // $(".start-date__alert").text(startDate);
            $(".current-date").text('روز اوّل' + ' (' + startDate + ')');

        }

        var startDateInfo = {
            'startDate' : startDate,
            'optionName' : optionName,
            'arbid' : arbid
        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
            },
            url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/manageStartDate/',
            type: 'POST',
            data: startDateInfo,
            success: (response) => {
                console.log(response);
                window.scrollTo(0,470);

                location.reload();
                // $(".start-date__alert").text(startDate);
                // alert(response);
            },
            error: (response) => {
                console.log(response);
                // alert("nope");
            }
        });
        $(".current-date").addClass("current-date_yellow");
        // $(".start-date__alert").text(startDate);
    }

    datepicker() {
        Mh1PersianDatePicker.Show();
    }

    //methods
  chooseDay(e) {

     var nthday = [
          "اول", "دوم", "سوم", "چهارم","پنجم","ششم", "هفتم", "هشتم", "نهم", "دهم",
          "یازدهم", "دوازدهم", "سیزدهم", "چهاردهم", "سیزدهم", "چهاردهم", "پانزدهم", "شانزدهم",
          "هفدهم", "هجدهم"
      ]
      return nthday[e];
  }

    sabtAmal() {
        var nthday = [
            "اول", "دوم", "سوم", "چهارم","پنجم","ششم", "هفتم", "هشتم", "نهم", "دهم",
            "یازدهم", "دوازدهم", "سیزدهم", "چهاردهم", "سیزدهم", "چهاردهم", "پانزدهم", "شانزدهم",
            "هفدهم", "هجدهم", "نوزدهم", "بیستم", "بیست و یکم", "بیست و دوم", "بیست و سوم", "بیست و چهارم",
            "بیست و پنجم", "بیست و ششم", "بیست و هفتم", "بیست و هشتم", "بیست و نهم", "سیم", "سی و یکم",
            "سی و دوم", "سی و سوم", "سی و چهارم", "سی و پنجم", "سی و ششم", "سی و هفتم", "سی و هشتم",
            "سی و نهم", "چهلم"
        ];

        let myList = [];
        var str = '';
        $("#submit-amal").addClass("hide");
        var arbId = $(".amal-table").data('arbid');
        var author = $(".amal-table").data('author');
        var day = $(".amal-table").data('day');
        var repeat = $(".amal-table").data('arbrepeat');
        // var repeat = $(".amal-table").data('arbrepeat');
        // var amals = $(".amal-table").data('amal');
        // console.log(amals);
        var rowNumber = 0;
        let amalidArr = [];
        var amalid = 0;
        var resultType = '';
        let resultTypes = [];
        $(".selector").each(function () {
            var thisval = $(this).val();
            var childTagName = $(this).children().first().prop("tagName");
            rowNumber = $(this).data('rownumber');
             amalid = $(this).data('amalid');
             resultType = $(this).data('resulttype');
            console.log('myamalid: ' + amalid);
            console.log('resType: ' + resultType);
            resultTypes.push(resultType);
            amalidArr.push(amalid);
            var arbayiinId = $(this).data('arbid');
            // console.log(childTagName + '- ' + rowNumber);
            var myName = 'result-' + rowNumber;

            switch (childTagName) {
                case 'DIV':
                    var resultValue = $(".resultInput-"+rowNumber +":checked").val();
                    console.log('result number ' + rowNumber + ': ' + resultValue);
                    str += (resultValue + '!@#');
                    myList.push(parseInt(resultValue));
                    // console.log('dd: ' + thisval);
                    break;
                default:
                    var textInput = $('#textarea-'+rowNumber).val();
                    // console.log('textInput: ' + textInput);
                    console.log('result number ' + rowNumber + ': ' + textInput);
                    if (textInput === ''){
                        str += '0!@#';
                        myList.push(0);
                        break;
                    }
                    myList.push(textInput);
                    str += textInput + '!@#';
                    break;
            }

            // console.log('resultString: ' + str);

        });
        // console.log('arbid: ' + arbId);
        // console.log('author: ' + author);
        // console.log('day: ' + day);
        // console.log('amals: ' + amalidArr);
        // console.log('rowszh counts: ' + rowNumber);
        // console.log('repeats: ' + repeat);
        // console.log(myList);

        var amaleruz = {
            'results': str,
            'arbayiin': arbId,
            'day': day,
            'author': author,
            'amals' : amalidArr,
            'resultsArray' : myList,
            'resulttype' : resultTypes,
            'repeat' : repeat
        }
        // alert("اعمال روز "+day + " با موفقیت ثبت شد.");
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
            },
            url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/createAmal/',
            type: 'POST',
            data: amaleruz,
            success: (response) => {
                console.log("Congrats");
                alert("اعمال روز "+day + " با موفقیت ثبت شد.");
                window.scrollTo(0,0);
                // console.log('response: ' +response);
                // var dayCounter = parseInt($(".ruz").data('daycount'), 10);
                // dayCounter++;
                // $(".ruz").html(nthday[dayCounter]);

                console.log(JSON.stringify(response));
                location.reload();
            },
            error: (response) => {
                console.log("Sorry");
                console.log(response);
            }
        });
    }

    deleteDispatcher(e) {

        $("body").addClass("body-no-scroll");
        $(".pop-outer h5").html(`<span>آیا برای حذف تمام نتایج مطمئن هستید؟</span>`);
        $(".pop-inner p").html(``);
        $(".pop-inner p").html(`<div>همه ی نتایج این اربعین حذف خواهند شد</div><br/><div class="cancel-delete btn btn--blue">انصراف</div>  <div class="confirm-delete btn btn--orange btn-outline-warning">حذف</div>`);
        $(".pop-outer").fadeIn("slow");
        $(".confirm-delete").on("click",   this.deleteResults.bind(this));
        $(".cancel-delete").on("click",  this.cancelDelete.bind(this));
    }

    cancelDelete(e) {
        $("body").removeClass("body-no-scroll");
        $(".pop-outer").fadeOut("slow");
    }

    deleteResults(e) {
        // console.log(e.currentTarget.data('arbid'));
        var arbId = $("span.delete-results").data("arbid");
        var arbrepeat = $("span.delete-results").data("arbrepeat");
        alert("نتایج این اربعین با موفقیت حذف شد.");
        console.log("repeatjgd: " + arbrepeat);
        var arbIdArray = {
            'arbid' : arbId,
            'arbrepeat' : arbrepeat
        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
            },
            url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/deleteResults/',
            type: 'DELETE',
            data: arbIdArray,
            success: (response) => {
                console.log(JSON.stringify(response));
                location.reload();
                // alert(response);
            },
            error: (response) => {
                console.log(response);
                // alert("nope");
            }
        });
    }
    ajax_delay(str){
        setTimeout("str",2000);
    }

    editProfile(e) {
        var thisForm = $(e.target).parents("ul");
        if (thisForm.data("state") == "editable") {
            //make read only
            this.makeFormReadOnly(thisForm);
        } else {
            // make editable
            this.makeFormEditable(thisForm)
        }
    }

    makeFormEditable(thisForm) {
        thisForm.find(".edit-profile").html('<i class="fa fa-times" aria-hidden="true"></i> انصراف');
        thisForm.find(".field-divided, .field-long").removeAttr("readonly").addClass("note-active-field");
        thisForm.find(".field-select").removeAttr("disabled").addClass("note-active-field");
        thisForm.find(".save-profile").addClass("save-profile--visible");
        thisForm.data("state", "editable");
    }

    makeFormReadOnly(thisForm) {
        thisForm.find(".edit-profile").html('<i class="fa fa-pencil" aria-hidden="true"></i> ویرایش');
        thisForm.find(".field-divided, .field-long").attr("readonly", "readonly").addClass("note-active-field");
        thisForm.find(".field-select").attr("disabled", "disabled").removeClass("note-active-field");
        thisForm.find(".save-profile").removeClass("save-profile--visible");
        thisForm.data("state", "cancel");
    }

    profileDispatcher(e) {
        var thisForm = $(e.target).parents("ul");
        if (thisForm.data("exists") == "yes") {
            // create new form for this user
            this.updateForm(thisForm);
        } else {
            // update the form of this user
            this.createForm(thisForm)
        }
    }

    createForm(thisForm) {
        var ourUpdatedForm = {
            'name': thisForm.find("#name").val(),
            'family': thisForm.find("#family").val(),
            'phone': thisForm.find("#phone").val(),
            'codemeli': thisForm.find("#codemeli").val(),
            'marriage': thisForm.find("#marriage option:selected").text(),
            'gender': thisForm.find("#gender option:selected").text(),
            'khadem': thisForm.find("#khadem").val(),
            'province': thisForm.find("#province").val(),
            'city': thisForm.find("#city").val(),
            'email': thisForm.find("#email").val(),
            'address': thisForm.find("#address").val(),
            'userid': thisForm.data('userid')
        }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
            },
            url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/manageProfile/',
            type: 'POST',
            data: ourUpdatedForm,
            success: (response) => {
                console.log("Congrats");
                console.log(response);
                this.makeFormReadOnly(thisForm);
            },
            error: (response) => {
                console.log("Sorry");
                console.log(response);
            }
        });
    }
    updateForm(thisForm) {

        var ourUpdatedForm = {
            'name': thisForm.find("#name").val(),
            'family': thisForm.find("#family").val(),
            'phone': thisForm.find("#phone").val(),
            'codemeli': thisForm.find("#codemeli").val(),
            'marriage': thisForm.find("#marriage option:selected").text(),
            'gender': thisForm.find("#gender option:selected").text(),
            'khadem': thisForm.find("#khadem").val(),
            'province': thisForm.find("#province").val(),
            'city': thisForm.find("#city").val(),
            'email': thisForm.find("#email").val(),
            'address': thisForm.find("#address").val(),
            'userid': thisForm.data('userid'),
            'id': thisForm.data('id')
        }

        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
            },
            url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/manageProfile/' + thisForm.data('id'),
            type: 'POST',
            data: ourUpdatedForm,
            success: (response) => {
                this.makeFormReadOnly(thisForm);
                console.log("Congrats");
                console.log(response);
            },
            error: (response) => {
                console.log("Sorry");
                console.log(response);
            }
        });


    }

}

export default Amal;