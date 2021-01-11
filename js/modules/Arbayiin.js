import $ from 'jquery';
class Arbayiin {
    // 2. describe and create/initiate our object
    constructor() {
        this.addARow = $("#addRow");
        this.tableBody = $(".myTableBody");
        this.removeRow = $("#remove")
        this.events();


}

// 2. events
events() {
this.addARow.on("click", this.addingRow.bind(this));
$(document).on("click", "#remove", this.removingRow);
}
// 3. methods (functions, action...)
    addingRow() {
        $('<tr><td><input type="text" name="name"></td><td><button id="remove" class="btn--orange">-</button></td></tr>').prependTo(".myTableBody");

    }
    removingRow() {
        $(this).parents('tr').remove();
    }
    addingColumn() {

    }
}
export default Arbayiin ;