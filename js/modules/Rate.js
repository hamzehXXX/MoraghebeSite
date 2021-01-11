import $ from 'jquery';

class Rate {
    constructor() {
        this.events();

    }

    events() {
        $(".rate-box").on("click", this.ourClickDispatcher.bind(this));
        $('#datepicker0').on("click", this.datePicker.bind(this));
    }

    // Methods
    datePicker() {
        this.datePicker();
    }
    ourClickDispatcher(e) {
        var currentRateBox = $(e.target).closest(".stars");

        if (currentRateBox.attr('data-exists') == 'yes') {
            this.deleteRate(currentRateBox);
        } else {
            this.createRate(currentRateBox, e);
        }


    }

    createRate(currentRateBox, e) {
        var rateValue = parseInt(currentRateBox.data('value'), 10);
        console.log(rateValue);

        var ourNewRate = {
            'author': currentRateBox.data('author'),
            'arbayiin': currentRateBox.data('arbayiinid'),
            'task': currentRateBox.data('taskid'),
            'day': currentRateBox.data('day')
        }
        console.log(ourNewRate);
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', moraghebehData.nonce);
            },
            url: moraghebehData.root_url + '/wp-json/moraghebeh/v1/manageRate',
            data: ourNewRate,
            type: 'POST',
            success: (response) => {
                if (rateValue == 1){
                currentRateBox.attr('data-exists', 'yes');
                }
                if (rateValue == 2) {
                    currentRateBox.attr('data-exists', 'yes');
                    currentRateBox.prev().attr('data-exists', 'yes');
                } if (rateValue == 3) {
                    currentRateBox.attr('data-exists', 'yes');
                    var secondStar = currentRateBox.prev();
                    secondStar.attr('data-exists', 'yes');
                    secondStar.prev().attr('data-exists', 'yes');
                }
                currentRateBox.attr("data-rate", response);
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }
}

export default Rate;