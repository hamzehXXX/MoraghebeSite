import $ from 'jquery';

class Profile {
    constructor() {
        this.events();
    }

    events() {
        $(".form-style-1").on("click", ".edit-profile", this.editProfile.bind(this));
        $(".form-style-1").on("click", "#phone", this.checkKeycode.bind(this));
        $(".form-style-1").on("click", ".save-profile", this.profileDispatcher.bind(this));
    }

    //methods
     checkKeycode(e) {
        var keycode=e.keyCode? e.keyCode : e.charCode
        if(isEnglishKeyCode(keycode)){
            //do something if u want
            alert('sldkfj');
        }
        else{
            void(0);
        }
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
            'birth': thisForm.find("#birth").val(),
            'phone': thisForm.find("#phone").val(),
            'codemeli': thisForm.find("#codemeli").val(),
            'marriage': thisForm.find("#marriage option:selected").text(),
            'gender': thisForm.find("#gender option:selected").text(),
            'khadem': thisForm.find("#khadem").val(),
            'province': thisForm.find("#province").val(),
            'city': thisForm.find("#city").val(),
            'email': thisForm.find("#email").val(),
            'address': thisForm.find("#address").val(),
            'phonehome': thisForm.find("#phonehome").val(),
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
            'birth': thisForm.find("#birth").val(),
            'phone': thisForm.find("#phone").val(),
            'codemeli': thisForm.find("#codemeli").val(),
            'marriage': thisForm.find("#marriage option:selected").text(),
            'gender': thisForm.find("#gender option:selected").text(),
            'khadem': thisForm.find("#khadem").val(),
            'province': thisForm.find("#province").val(),
            'city': thisForm.find("#city").val(),
            'email': thisForm.find("#email").val(),
            'address': thisForm.find("#address").val(),
            'phonehome': thisForm.find("#phonehome").val(),
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

export default Profile;