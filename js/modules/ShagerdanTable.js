import $ from 'jquery';

class ShagerdanTable {


    constructor() {
        this.events();
    }

    events() {
       var properties = [
            'name',
            'wins',
            'draws',
            'losses',
            'total',
        ];
        $.each( properties, function( i, val ) {

            var orderClass = '';

            $("#" + val).click(function(e){
                e.preventDefault();
                $('.filter__link.filter__link--active').not(this).removeClass('filter__link--active');
                $(this).toggleClass('filter__link--active');
                $('.filter__link').removeClass('asc desc');

                if(orderClass == 'desc' || orderClass == '') {
                    $(this).addClass('asc');
                    orderClass = 'asc';
                } else {
                    $(this).addClass('desc');
                    orderClass = 'desc';
                }

                var parent = $(this).closest('.shagerdan-header__item');
                var index = $(".shagerdan-header__item").index(parent);
                var $table = $('.shagerdan-table-content');
                var rows = $table.find('.shagerdan-table-row').get();
                var isSelected = $(this).hasClass('filter__link--active');
                var isNumber = $(this).hasClass('filter__link--number');

                rows.sort(function(a, b){

                    var x = $(a).find('.shagerdan-table-data').eq(index).text();
                    var y = $(b).find('.shagerdan-table-data').eq(index).text();

                    if(isNumber == true) {

                        if(isSelected) {
                            return x - y;
                        } else {
                            return y - x;
                        }

                    } else {

                        if(isSelected) {
                            if(x < y) return -1;
                            if(x > y) return 1;
                            return 0;
                        } else {
                            if(x > y) return -1;
                            if(x < y) return 1;
                            return 0;
                        }
                    }
                });

                $.each(rows, function(index,row) {
                    $table.append(row);
                });

                return false;
            });

        });

        $(window).scroll(function(e){
            var $el = $('.shagerdan-table-header');
            var isPositionFixed = ($el.css('position') == 'fixed');
            if ($(this).scrollTop() > 360 && !isPositionFixed){
                $el.css({'position': 'fixed', 'top': '0px'});
            }
            if ($(this).scrollTop() < 360 && isPositionFixed){
                $el.css({'position': 'static', 'top': '0px'});
            }
        });
    }
}

export default ShagerdanTable;