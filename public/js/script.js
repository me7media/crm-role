        //при нажатии на ячейку таблицы с классом edit
        $(document).on('click', 'td.edit', function(){
            //находим input внутри элемента с классом ajax и вставляем вместо input его значение
            $('.ajax').html($('.ajax input').val());
            //удаляем все классы ajax
            $('.ajax').removeClass('ajax');
            //Нажатой ячейке присваиваем класс ajax
            $(this).addClass('ajax');
            //внутри ячейки создаём input и вставляем текст из ячейки в него
            var text = $(this).text();
            $(this).html('<input id="editbox" size="50%" value="' + text + '" type="text">');
            //устанавливаем фокус на созданном элементе
            $('#editbox').focus();

$(this).on('blur', '#editbox', function(){
$('.ajax').html($('.ajax input').val());
$('.ajax').removeClass('ajax');

});

        });
$(document).on('change', 'td.edit', function(){
    var id = $(this).attr('id');
        $('input[id='+id+']').val($('#editbox').val());
});