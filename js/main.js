/**
 * Created by aabweber on 07/09/2019.
 * Класс обработки формы для ставок
 */

$(function() {
    class Bet{
        /** Создание формы */
        constructor(name) {
            this.name = name;
            $('#makeBet').on('shown.bs.modal', function () {
                $('#makeBet').trigger('focus')
            })
        }

        /** Запрос выполнения от сервера */
        request(url){
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url, false);
            xhr.send();
            if (xhr.status != 200) {
                // обработать ошибку
                console.log( xhr.status + ': ' + xhr.statusText );
            } else {
                return xhr.responseText;
            }
        }

        /** Вешаем хуки на обработку формы */
        initForm(){
            $('#betFormSubmit').click(function(e){
                    e.preventDefault();
                    var bet = document.getElementById('#bet').value();
                    alert (bet);
                }
            );
            $('#makeBet .btn-primary').click(function(e) {
                $('#betForm').submit();
            });

            $(document).ready(function() {
                $('button.close').attr('data-toggle', 'modal');
            });
        }
    }
    /** Создаёем класс и инициализируем его */
    var bet = new Bet();
    bet.initForm();
});


