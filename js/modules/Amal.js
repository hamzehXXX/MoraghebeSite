import $ from 'jquery';

class Amal {
    constructor() {
        this.events();
    }

    events() {
        $("#submit-amal").on("click",  this.sabtAmal.bind(this));
        $("button#submit-date").on("click",  this.sabtDate.bind(this));

    }

    sabtDate() {
        var startDate = $("input.start-date").val();
        var userid = $("input.start-date").data("userid");
        var arbid = $("input.start-date").data("arbid");
        var optionName = userid + "-" + arbid;
        // alert(optionName);
        if (startDate == '') {
            $(".arbayiin-table, .generic-content").addClass("hide-table");
            $(".start-date__alert").text('لطفا تاریخ شروع را مشخص بفرمایید');
        } else {
            $(".arbayiin-table, .generic-content").removeClass("hide-table");
            $(".start-date__alert").text(startDate);
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
                // alert(response);
            },
            error: (response) => {
                console.log(response);
                // alert("nope");
            }
        });
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
        var inputVal = $('input[name="result-1"]:checked').val();

        var day = "روز";
        var ruz = $(".ruz").text();
        var amalsize = $(".jsResults").data('amalsize');
        var author = $(".ruz").data('author');


        var nthday = [
            "اول", "دوم", "سوم", "چهارم","پنجم","ششم", "هفتم", "هشتم", "نهم", "دهم",
            "یازدهم", "دوازدهم", "سیزدهم", "چهاردهم", "سیزدهم", "چهاردهم", "پانزدهم", "شانزدهم",
            "هفدهم", "هجدهم", "نوزدهم", "بیستم", "بیست و یکم", "بیست و دوم", "بیست و سوم", "بیست و چهارم",
            "بیست و پنجم", "بیست و ششم", "بیست و هفتم", "بیست و هشتم", "بیست و نهم", "سیم", "سی و یکم",
            "سی و دوم", "سی و سوم", "سی و چهارم", "سی و پنجم", "سی و ششم", "سی و هفتم", "سی و هشتم",
            "سی و نهم", "چهلم"
        ];

        let myList = [];
        // zakhire amale sabt shod dar yek string ba joda konandeye ',' ke dar amal-route b surate array begirimesh ba explode

        // $(".select-css option:selected").each(function () {
        //     myList.push($(this).text());
        //     myList.push($("#resultmatni").val());
        //     str += $(this).text() + ',';
        //     // if ($(this).data('matni') === true){
        //         str += $(this).val() + ',';
        //     // }
        //
        // });
        var str = '';
        // $(".selector").each(function () {
        //     var thisval = $(this).val();
        //     var childTagName = $(this).children().first().prop("tagName");
        //     var rowNumber = $(this).data('rownumber');
        //     console.log(childTagName + '- ' + rowNumber);
        //     var myName = 'result-' + rowNumber;
        //
        //
        //     switch (childTagName) {
        //         case 'DIV':
        //             str += ($(".resultInput-"+rowNumber +":checked").val() + ',');
        //             // console.log('dd: ' + thisval);
        //             break;
        //         default:
        //             var textInput = $('textarea#textarea-'+rowNumber).val();
        //             console.log('textInput: ' + textInput);
        //             if (textInput === ''){
        //                 str += '0,';
        //                 break;
        //             }
        //             str += textInput + ',';
        //             break;
        //     }
        //
        //     console.log('rn: ' + str);
        // });


        $(".selector").each(function () {
            var thisval = $(this).val();
            var childTagName = $(this).children().first().prop("tagName");
            var rowNumber = $(this).data('rownumber');
            console.log(childTagName + '- ' + rowNumber);
            var myName = 'result-' + rowNumber;


            switch (childTagName) {
                case 'DIV':
                    var resultValue = $(".resultInput-"+rowNumber +":checked").val();
                    str += (resultValue + '!@#');
                    myList.push(resultValue);
                    // console.log('dd: ' + thisval);
                    break;
                default:
                    var textInput = $('#textarea-'+rowNumber).val();
                    console.log('textInput: ' + textInput);
                    if (textInput === ''){
                        str += '0!@#';
                        myList.push('0');
                        break;
                    }
                    myList.push(textInput);
                    str += textInput + '!@#';
                    break;
            }
            console.log(myList);
            console.log('rn: ' + str);
            // console.log($(this).prop("tagName"));
            // $(this).val()===''?str += '0,':str += ($(this).text() + ',');

        });

        var amaleruz = {
            'results': str,
            'arbayiin': $(".ruz").data('arbayiin-id'),
            'day': ruz,
            'author': author
        }

        // $.ajax({
        //     beforeSend: (xhr) => {
        //         xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
        //     },
        //     url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/createAmal/',
        //     type: 'POST',
        //     data: amaleruz,
        //     success: (response) => {
        //         // $(`     <li style="float: right; border: 1px solid #ECECEC;" class="selected-vendor" >
        //         //     <div class="dday" style="background: #ECECEC; border: 1px solid #ECECEC;">${ruz}</div>`).appendTo("#results").show().slideDown();
        //         // $(".dday").addClass("mday");
        //         // for (let i = 0; i < myList.length; ++i) {
        //         //     $(`
        //         //                 <div style="background: #ECECEC; border: 1px solid #ECECEC;">${myList[i]}</div>
        //         // </li>
        //         // `).appendTo('.mday').show().slideDown();
        //         // }
        //         // $(".dday").removeClass('mday');
        //         // $(".dday").removeClass('dday');
        //
        //         setTimeout(function() {
        //             console.log('response: ' +response);
        //         }, 5000);
        //         console.log("Congrats");
        //         console.log('response: ' +response);
        //         var dayCounter = parseInt($(".ruz").data('daycount'), 10);
        //         // dayCounter++;
        //         // $(".ruz").html(nthday[dayCounter]);
        //         alert("اعمال روز "+nthday[dayCounter] + " با موفقیت ثبت شد.")
        //         // location.reload();
        //         location.reload();
        //     },
        //     error: (response) => {
        //         console.log("Sorry");
        //         console.log(response);
        //     }
        // });
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