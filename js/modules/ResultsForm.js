import $ from 'jquery';


class ResultsForm {
    constructor() {
        this.events();
    }

    events() {
        $(".results-form__submit").on("click", this.createForm.bind(this));
    }

    // Methods
    createForm(e) {
        var repeat =$(".results-form__submit").data('repeat');
        console.log(repeat);
        var ourNewForm = {
            'halat': $("#halat").val(),
            'vaziyat': $("#vaziyat").val(),
            'khab': $("#khab").val(),
            'arbayiinid': $(".results-form__submit").data('arbayiinid'),
            'repeat': repeat

        }
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
            },
            url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/manageResultsForm/',
            type: 'POST',
            data: ourNewForm,
            success: (response) => {
                $(".results-form").css('display', 'none');

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

}

export default ResultsForm;